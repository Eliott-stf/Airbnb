<?php

namespace App\Controller;


use Exception;
use App\Entity\Post;
use App\Entity\Type;
use App\Entity\Category;
use App\Entity\Equipment;
use App\Entity\Available;
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
use App\Repository\EquipmentRepository;
use JulienLinard\Doctrine\EntityManager;
use JulienLinard\Router\Attributes\Route;
use JulienLinard\Core\Logging\SimpleLogger;
use JulienLinard\Core\Controller\Controller;
use JulienLinard\Auth\Middleware\AuthMiddleware;

class HoteController extends Controller
{
    private AuthManager $auth;
    private EntityManager $em;
    private PostRepository $postRepository;
    private TypeRepository $typeRepository;
    private EquipmentRepository $equipmentRepository;
    private CategoryRepository $categoryRepository;
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
        if ($country === '') {
            $country = 'Non renseigné';
        }
        if (empty($title)) {
            $errors['title'] = 'Le titre est requis';
        } elseif (strlen($title) > 255) {
            $errors['title'] = 'Le titre ne doit pas dépasser 255 caractères';
        }

        if (empty($description)) {
            $errors['description'] = "La description est requise";
        }

        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs du formulaire');

            return $this->view('hote/create', [
                'errors' => $errors,
                "csrf_token" => ViewHelper::csrfToken(),
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
                'old' => [
                    'title' => $title ?? '',
                    'description' => $description ?? '',
                    'price_day' => $priceDay,
                    'country' => $country,
                    'address' => $address,
                    'postal_code' => $postalCode,
                    'city' => $city,
                    'bed_count' => $bedCount,
                    'max_capacity' => $maxCapacity
                ],
                'errors' => ['general' => $e->getMessage()]
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
