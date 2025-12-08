<?php

namespace App\Controller;


use Exception;
use App\Entity\Post;
use App\Entity\Type;
use App\Entity\Category;
use App\Entity\Available;
use App\Entity\Equipment;
use JulienLinard\Router\Request;
use JulienLinard\Router\Response;
use App\Repository\PostRepository;
use App\Repository\TypeRepository;
use App\Service\FileUploadService;
use JulienLinard\Auth\AuthManager;
use JulienLinard\Core\Application;
use JulienLinard\Core\Form\Validator;
use App\Repository\CategoryRepository;
use JulienLinard\Core\Session\Session;
use JulienLinard\Core\View\ViewHelper;
use App\Repository\AvailableRepository;
use App\Repository\EquipmentRepository;
use JulienLinard\Doctrine\EntityManager;
use JulienLinard\Router\Attributes\Route;
use JulienLinard\Core\Logging\SimpleLogger;
use JulienLinard\Core\Controller\Controller;
use JulienLinard\Validator\Rules\RequiredRule;
use JulienLinard\Auth\Middleware\AuthMiddleware;

class HoteController extends Controller
{
    private AuthManager $auth;
    private EntityManager $em;
    private PostRepository $postRepository;
    private TypeRepository $typeRepository;
    private EquipmentRepository $equipmentRepository;
    private CategoryRepository $categoryRepository;
    private AvailableRepository $availableRepository;
    private Validator $validator;
    private FileUploadService $fileUploadService; // service d'upload de média
    private SimpleLogger $logger;

    public function __construct()
    {
        $app = Application::getInstanceOrFail();
        $container = $app->getContainer();

        $this->auth = $container->make(AuthManager::class);
        $this->em = $container->make(EntityManager::class);
        $this->postRepository = $this->em->createRepository(PostRepository::class, Post::class);
        $this->availableRepository = $this->em->createRepository(AvailableRepository::class, Available::class);
        $this->typeRepository = $this->em->createRepository(TypeRepository::class, Type::class);
        $this->equipmentRepository = $this->em->createRepository(EquipmentRepository::class, Equipment::class);
        $this->categoryRepository = $this->em->createRepository(CategoryRepository::class, Category::class);
        $this->validator = new Validator();
        $this->fileUploadService = new FileUploadService();
        $this->logger = $container->make(SimpleLogger::class);
    }


    #[Route(path: "/hote/index", name: "app_dashboard", middleware: [AuthMiddleware::class])]
    public function dashboard(): Response
    {
        try {
            // Récupère l'utilisateur connecté
            $user = $this->auth->user();

            // Récupère les annonces du user
            $posts = $this->postRepository->findByUserId($user->id);

            $this->logger->info("Dashboard consulté", [
                "user_id" => $user->id,
                "posts_count" => count($posts)
            ]);

            return $this->view("hote/index", [
                "title" => "Mes annonces",
                "user" => $user,
                "posts" => $posts
            ]);
        } catch (Exception $e) {

            $this->logger->error($e->getMessage(), [
                "action" => "Dashboard",
                "trace" => $e->getTraceAsString()
            ]);

            return $this->view("hote/index", [
                "title" => "Mes annonces",
                "user" => $this->auth->user(),
                "posts" => [],
                "error" => $e
            ]);
        }
    }


    /**
     * méthod pour afficher le formulaire de création
     * @return Response
     */
    #[Route(path: "/hote/create", name: "app_hote_create", middleware: [AuthMiddleware::class])]
    public function createForm(): Response
    {
        $types = $this->typeRepository->findAll();
        $equipments = $this->equipmentRepository->findAll();
        $categories = $this->categoryRepository->findAll();

        return $this->view("hote/create", [
            "csrf_token" => ViewHelper::csrfToken(),
            "user" => $this->auth->user(),
            "types" => $types,
            'equipments' => $equipments,
            'categorys' => $categories
        ]);
    }

    /**
     * méthod pour enregistrer les données du form de création de bien 
     * @return Response
     */
    #[Route(path: "/hote/create", name: "app_hote_create_POST", methods: ["POST"], middleware: [AuthMiddleware::class])]
    public function create(Request $request): Response
    {
        //on verifie l'user 
        $user = $this->auth->user();
        if (!$user) {
            return $this->redirect('/login');
        }

        $types = $this->typeRepository->findAll();
        $equipments = $this->equipmentRepository->findAll();
        $categories = $this->categoryRepository->findAll();

        //On récupère les infos du post dans des variables

        $title = trim($request->getPost('title', '') ?? '');
        $description = trim($request->getPost('description', '') ?? '');
        $priceDay = trim($request->getPost('price_day', '0') ?? '0');
        $country = trim($request->getPost('country', '') ?? '');
        $address = trim($request->getPost('address', '') ?? '');
        $postalCode = trim($request->getPost('postal_code', '') ?? '');
        $city = trim($request->getPost('city', '') ?? '');
        $bedCount = trim($request->getPost('bed_count', '0') ?? '0');
        $maxCapacity = trim($request->getPost('max_capacity', '0') ?? '0');
        $typeId = trim($request->getPost('type_id', '0') ?? '0');
        $equipmentIds = $request->getPost('equipment_ids', []);
        $dateIn = $request->getPost('date_in', '0') ?? '0';
        $dateOut = $request->getPost('date_out', '0') ?? '0';

        $dateInObject = new \DateTime($dateIn);
        $dateOutObject = new \DateTime($dateOut);


        //On effectue les validations 
        $validator = new Validator();

        //Titre requis & min/max 
        //Description requis & min/max
        //PriceDay requis & numéric & max 1000
        //Country requis & string 
        //addresse requis & max 
        //PostalCode requis & is_numeric max 5
        //City requis & string & max
        //BedCount requis & is_numeric & max 10
        //MaxCapacity requis & is_numeric & max 10


        $result = $validator->validate($_POST, [
            'title'     => 'required|min:3|max:255',
            'description' => 'required|min:10',
            'price_day' => 'required|numeric|max:1000',
            'country' => 'required|pattern:/^[A-Za-zÀ-ÿ\-\' ]+$/',
            'address' => 'required|max:150',
            'postal_code' => 'required|pattern:/^[0-9]{5}$/',
            'city' => 'required|pattern:/^[A-Za-zÀ-ÿ\-\' ]+$/|max:150',
            'bed_count' => 'required',
            'max_capacity' => 'required',
        ]);


        if ($result->hasErrors()) {

            Session::flash('error', 'Veuillez corriger les erreurs du formulaire');

            return $this->view('hote/create', [
                "csrf_token" => ViewHelper::csrfToken(),
                "user"       => $user,
                "types"      => $types,
                "equipments" => $equipments,
                "categorys"  => $categories,
                "errors"     => $result->getErrors(),
                'old' => [
                    'title' => $title ?? '',
                    'description' => $description ?? '',
                    'price_day' => $priceDay,
                    'country' => $country,
                    'address' => $address,
                    'postal_code' => $postalCode,
                    'city' => $city,
                    'date_in' => $dateIn,
                    'date_out' => $dateOut,
                    'bed_count' => $bedCount,
                    'max_capacity' => $maxCapacity
                ]
            ]);
        }

        $mediaPath = null;

        if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {

            // On récupère un UploadResult
            $result = $this->fileUploadService->upload($_FILES['media']);

            // Vérifier si c'est bien un UploadResult
            if ($result instanceof \App\Service\UploadResult && $result->isSuccess()) {

                $data = $result->getData(); // array ou null

                if ($data !== null && isset($data['path'])) {
                    $mediaPath = $data['path'];   // <-- ICI : string OK
                }
            }
        }


        //On créer le post 
        try {
            $post = new Post();
            $post->title = $title;
            $post->description = $description;
            $post->user_id = $user->getAuthIdentifier();
            $post->type_id = $typeId;
            $post->price_day = $priceDay;
            $post->country = $country;
            $post->address = $address;
            $post->postal_code = $postalCode;
            $post->city = $city;
            $post->bed_count = $bedCount;
            $post->max_capacity = $maxCapacity;
            $post->media_path = $mediaPath;
            $post->created_at = new \DateTime();

            
            $this->em->persist($post);
            $this->em->flush();


            $available = new Available();
            $available->date_in = $dateInObject;
            $available->date_out = $dateOutObject;
            $available->post_id = $post->id;
            $this->em->persist($available);
            $this->em->flush();



            Session::flash("Post créer avec succes", ["user_id" => $user->id, "title_post" => $title]);
            //redirection 
            return $this->redirect("/hote/index");
        } catch (\Exception $e) {
            Session::flash('error', 'Une erreur est survenue lors de la création du todo: ' . $e->getMessage());


            return $this->view('hote/create', [
                "csrf_token" => ViewHelper::csrfToken(),
                "user"       => $user,
                "types"      => $types,
                "equipments" => $equipments,
                "categorys"  => $categories,
                "errors"     => $result->getErrors(),
                'old' => [
                    'title' => $title ?? '',
                    'description' => $description ?? '',
                    'price_day' => $priceDay,
                    'country' => $country,
                    'address' => $address,
                    'postal_code' => $postalCode,
                    'city' => $city,
                    'date_in' => $dateIn,
                    'date_out' => $dateOut,
                    'bed_count' => $bedCount,
                    'max_capacity' => $maxCapacity
                ]
            ]);
        }
    }


    /**
     * méthode pour edit un post
     * @param int $id
     * @return Response 
     */

    #[Route(path: "/hote/{id}/edit", name: "app_edit_post", methods: ["GET"], middleware: [AuthMiddleware::class])]
    public function editForm(int $id): Response
    {
        try {
            $user = $this->auth->user();

            $post = $this->postRepository->findByIdAndUserId($id, $user->id);

            if (!$post) {
                Session::flash("Post non trouvé lors de l'édition", ["post_id" => $id, "user_id" => $user->id]);
                return $this->redirect("/hote/index");
            }

            $types = $this->typeRepository->findAll();
            $equipments = $this->equipmentRepository->findAll();
            $categories = $this->categoryRepository->findAll();
            $available = $this->availableRepository->findByIdAndUserId($id, $user->id);


            return $this->view("hote/edit", [
                "csrf_token" => ViewHelper::csrfToken(),
                "user" => $this->auth->user(),
                "types" => $types,
                'equipments' => $equipments,
                'categorys' => $categories,
                "available" => $available,
                "post" => $post
            ]);
        } catch (Exception $e) {
            Session::flash($e, ["post_id" => $id, "action" => "edit_post"]);
            return $this->redirect("/hote/index");
        }
    }


    /**
     * méthode pour enregistrer les modifications au post 
     * @param int $id id du todo 
     * @param Request $request
     * @return Response
     */
    #[Route(path: "/hote/{id}/edit", name: "app_edit_post", methods: ["POST"], middleware: [AuthMiddleware::class])]
    public function edit(int $id, Request $request): Response
    {
        $user = $this->auth->user();
        if (!$user) {
            return $this->redirect('/login');
        }

        $post = $this->postRepository->findByIdAndUserId($id, $user->id);
        if (!$post) {
            Session::flash('error', 'Post introuvable');
            return $this->redirect('/hote/index');
        }

        $types = $this->typeRepository->findAll();
        $equipments = $this->equipmentRepository->findAll();
        $categories = $this->categoryRepository->findAll();

        // Récupération des champs du formulaire
        $title = trim($request->getPost('title', '') ?? '');
        $description = trim($request->getPost('description', '') ?? '');
        $priceDay = trim($request->getPost('price_day', '0') ?? '0');
        $country = trim($request->getPost('country', '') ?? '');
        $address = trim($request->getPost('address', '') ?? '');
        $postalCode = trim($request->getPost('postal_code', '') ?? '');
        $city = trim($request->getPost('city', '') ?? '');
        $bedCount = trim($request->getPost('bed_count', '0') ?? '0');
        $maxCapacity = trim($request->getPost('max_capacity', '0') ?? '0');
        $typeId = trim($request->getPost('type_id', '0') ?? '0');
        $equipmentIds = $request->getPost('equipment_ids', []);
        $dateIn = $request->getPost('date_in', '0') ?? '0';
        $dateOut = $request->getPost('date_out', '0') ?? '0';
        $removeMedia  = $request->getPost('remove_media', '0') === "1";

        $dateInObject = new \DateTime($dateIn);
        $dateOutObject = new \DateTime($dateOut);

        // Validation
        $validator = new Validator();
        $result = $validator->validate($_POST, [
            'title'        => 'required|min:3|max:255',
            'description'  => 'required|min:10',
            'price_day'    => 'required|numeric|max:1000',
            'country'      => 'required|pattern:/^[A-Za-zÀ-ÿ\-\' ]+$/',
            'address'      => 'required|max:150',
            'postal_code'  => 'required|pattern:/^[0-9]{5}$/',
            'city'         => 'required|pattern:/^[A-Za-zÀ-ÿ\-\' ]+$/|max:150',
            'bed_count'    => 'required',
            'max_capacity' => 'required',
        ]);

        if ($result->hasErrors()) {
            Session::flash('error', 'Veuillez corriger les erreurs du formulaire');
            return $this->view('hote/edit', [
                "csrf_token" => ViewHelper::csrfToken(),
                "user"       => $user,
                "types"      => $types,
                "equipments" => $equipments,
                "categorys"  => $categories,
                "errors"     => $result->getErrors(),
                'old' => [
                    'title' => $title,
                    'description' => $description,
                    'price_day' => $priceDay,
                    'country' => $country,
                    'address' => $address,
                    'postal_code' => $postalCode,
                    'city' => $city,
                    'date_in' => $dateIn,
                    'date_out' => $dateOut,
                    'bed_count' => $bedCount,
                    'max_capacity' => $maxCapacity
                ]
            ]);
        }

        // Gestion du media
        $mediaPath = $post->media_path;

        if ($removeMedia && $mediaPath) {
            $this->fileUploadService->delete($mediaPath);
            $mediaPath = null;
        }

        if (isset($_FILES['media']) && $_FILES['media']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->fileUploadService->upload($_FILES['media']);
            if ($uploadResult instanceof \App\Service\UploadResult && $uploadResult->isSuccess()) {
                $data = $uploadResult->getData();
                if ($data !== null && isset($data['path'])) {
                    $mediaPath = $data['path'];
                }
            }
        }

        try {
            // Mise à jour du post
            $post->title = $title;
            $post->description = $description;
            $post->type_id = $typeId;
            $post->price_day = $priceDay;
            $post->country = $country;
            $post->address = $address;
            $post->postal_code = $postalCode;
            $post->city = $city;
            $post->bed_count = $bedCount;
            $post->max_capacity = $maxCapacity;
            $post->media_path = $mediaPath;

            $this->em->persist($post);
            $this->em->flush();

            // Mise à jour des disponibilités
            $available = $this->availableRepository->findByIdAndUserId($id, $user->id);
            if (!$available) {
                $available = new Available();
                $available->post_id = $post->id;
            }

            $available->date_in = $dateInObject;
            $available->date_out = $dateOutObject;

            $this->em->persist($available);
            $this->em->flush();

            Session::flash("Post modifié avec succes", ["user_id" => $user->id, "title_post" => $title]);
            return $this->redirect("/hote/index");
        } catch (\Exception $e) {
            Session::flash('error', 'Une erreur est survenue lors de la modification du post: ' . $e->getMessage());
            return $this->view('hote/edit', [
                "csrf_token" => ViewHelper::csrfToken(),
                "user"       => $user,
                "types"      => $types,
                "equipments" => $equipments,
                "categorys"  => $categories,
                "errors"     => $result->getErrors(),
                'old' => [
                    'title' => $title,
                    'description' => $description,
                    'price_day' => $priceDay,
                    'country' => $country,
                    'address' => $address,
                    'postal_code' => $postalCode,
                    'city' => $city,
                    'date_in' => $dateIn,
                    'date_out' => $dateOut,
                    'bed_count' => $bedCount,
                    'max_capacity' => $maxCapacity
                ]
            ]);
        }
    }



    /**
     * Méthode pour supprimer un post
     * @param int $id id du post
     * @return Response
     */
    #[Route(path: "/hote/{id}/delete", name: "app_delete_post", methods: ["POST"], middleware: [AuthMiddleware::class])]
    public function delete(int $id): Response
    {

        try {
            $user = $this->auth->user();


            $post = $this->postRepository->findByIdAndUserId($id, $user->id);
            if (!$post) {
                Session::flash("Post non trouvé lors de la suppression", ["post_id" => $id, "user_id" => $user->id]);
                return $this->redirect("/hote");
            }


            if ($post->media_path) {
                $this->fileUploadService->delete($post->media_path);
            }


            $this->em->remove($post);
            $this->em->flush();

            Session::flash("Post supprimé avec succès", ["post_id" => $id, "user_id" => $user->id]);
            return $this->redirect("/hote/index");
        } catch (Exception $e) {
            Session::flash($e, ["post_id" => $id, "action" => "remove_post"]);
            return $this->redirect("/hote/index");
        }
    }
}
