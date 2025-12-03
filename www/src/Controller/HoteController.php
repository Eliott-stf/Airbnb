<?php

namespace App\Controller;

use App\Entity\Post;
use JulienLinard\Router\Response;
use App\Service\FileUploadService;
use JulienLinard\Auth\AuthManager;
use JulienLinard\Core\Form\Validator;
use JulienLinard\Doctrine\EntityManager;
use JulienLinard\Router\Attributes\Route;
use JulienLinard\Core\Controller\Controller;

class HoteController extends Controller
{
    public function __construct(
        private AuthManager $auth,
        private EntityManager $em
    ) {}

    //TODO: MIDDLEWARE
    #[Route("/hote/create", methods: ["GET"], name: "app_hote")]
    public function postForm()
    {
        return $this->view("hote/create");
    }

    /**
     * méthod pour enregistrer les données du post a sa création
     * @return Response
     */

    #[Route("/hote/create", methods: ["POST"], name: "app_add_post")]
    public function postCreate(): Response
    {
        try {
            $user = $this->auth->user();

            //On décalre nos variables a stocker du form
            $title = trim($_POST['title'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $priceDay = $_POST['price_day'];
            $country = trim($_POST['country'] ?? '');
            $address = trim($_POST['address'] ?? '');
            $postalCode = trim($_POST['postal_code'] ?? '');
            $city = trim($_POST['city'] ?? '');
            $bedCount = trim($_POST['bed_count'] ?? '');
            $maxCapacity = trim($_POST['max_capacity'] ?? '');

            //On procède a la validation des infos
            /* $isValid = true;
            $isValid = $this->validator->($title) && */

            $post = new Post();
            $post->title = $title;
            $post->description = $description;
            $post->price_day = $priceDay;
            $post->country = $country;
            $post->address = $address;
            $post->postal_code = $postalCode;
            $post->city = $city;
            $post->bed_count = $bedCount;
            $post->max_capacity = $maxCapacity;
            $post->user = $user; // Relation Doctrine ou équivalent
            $post->created_at = new \DateTime();

            //save en bdd
            $this->em->persist($post);
            $this->em->flush();

            Logger::info("Post créer avec succer", ["user_id" => $user->id, "title_post" => $title]);
            //redicrection
            return $this->redirect("/");
        } catch (\Exception $e) {
            Logger::exception("$e", [
                "action" => "create_post",
                "user_id" => $this->auth->user()->id
            ]);

            return $this->view("hote/create", [
                "user" => $this->auth->user(),
                "error" => "Une erreur est survenue pendant la création de la tache. Veuillez réessayer.",
                "csrf_token" => ViewHelper::csrfToken(),
            ]);
        }
    }
}
