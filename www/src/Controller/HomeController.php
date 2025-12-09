<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\Type;
use App\Entity\Booking;
use App\Entity\Available;
use App\Entity\Equipment;
use JulienLinard\Router\Request;
use JulienLinard\Router\Response;
use App\Repository\PostRepository;
use App\Repository\TypeRepository;
use JulienLinard\Auth\AuthManager;
use App\Repository\BookingRepository;
use JulienLinard\Core\Session\Session;
use App\Repository\AvailableRepository;
use App\Repository\EquipmentRepository;
use JulienLinard\Doctrine\EntityManager;
use JulienLinard\Router\Attributes\Route;
use JulienLinard\Core\Controller\Controller;
use JulienLinard\Auth\Middleware\AuthMiddleware;

class HomeController extends Controller
{
    private PostRepository $postRepository;
    private BookingRepository $bookingRepository;
    private TypeRepository $typeRepository;
    private EquipmentRepository $equipmentRepository;
    private AvailableRepository $availableRepository; 

    public function __construct(
        private EntityManager $em,
        private AuthManager $auth
    ) {
        $this->postRepository = $this->em->createRepository(PostRepository::class, Post::class);
        $this->bookingRepository = $this->em->createRepository(BookingRepository::class, Booking::class);
        $this->typeRepository = $this->em->createRepository(TypeRepository::class, Type::class);
        $this->equipmentRepository = $this->em->createRepository(EquipmentRepository::class, Equipment::class);
        $this->availableRepository = $this->em->createRepository(AvailableRepository::class, Available::class); 
    }

    #[Route("/", name: "app_index")]
    public function index(): Response
    {
        $posts = $this->postRepository->findAll();
        $postList = $this->postRepository->findBy(['user_id' => $this->auth->id()]);
        $user = $this->auth->user();

        $data = [
            "posts" => $posts,
            "post" => $postList,
            "user" => $user
        ];

        return $this->view("home/index", $data);
    }



#[Route(path: "/post/{id}", name: "app_show", methods: ["GET"])]
public function post(int $id): Response
{
    $post = $this->postRepository->find($id);

    if (!$post) {
        return $this->redirect('/'); 
    }

    $equipments = $this->equipmentRepository->findByPostId($id); 
    $postList = $this->postRepository->findBy(['user_id' => $this->auth->id()]);
    $available = $this->availableRepository->findOneBy(['post_id' => $post->id]);
    $booking = $this->bookingRepository->find($id);
    $type = $this->typeRepository->find($post->type_id);

    return $this->view("home/show", [
        "post" => $post,
        "posts" => $postList,
        "user" => $this->auth->user(),
        "booking" => $booking,
        "type" => $type,
        "available" => $available,
        "equipments" => $equipments 
    ]);
}
    /**
     * méthod pour récuperer les données du form de réservation 
     * @param Request $request
     * @param int $id
     * @return Response 
     */

    #[Route(path: "/post/{id}", name: "app_post_show", methods: ["POST"], middleware: [AuthMiddleware::class])]
    public function postForm(Request $request, int $id): Response
    {
        //on verifie l'user 
        $user = $this->auth->user();
        if (!$user) {
            return $this->redirect('/login');
        }

        $post = $this->postRepository->find($id);

        // On récupère les infos de la reservation 
        $dateIn = $request->getPost('date_in', '0') ?? '0';
        $dateOut = $request->getPost('date_out', '0') ?? '0';
        $dateInObject = new \DateTime($dateIn);
        $dateOutObject = new \DateTime($dateOut);
        $guestCount = $request->getPost('guest_count', '0') ?? '0';

        //on recup les info + envoi en bdd
        try {
            $booking = new Booking;
            $booking->date_in = $dateInObject;
            $booking->date_out = $dateOutObject;
            $booking->guest_count = $guestCount;
            $booking->user_id = $user->getAuthIdentifier();
            $booking->post_id = $post->id;
            $this->em->persist($booking);
            $this->em->flush();

            Session::flash("Reservation créer avec succes", ["user_id" => $user->id, "post_id" => $post->id]);
            //redirection 
            return $this->redirect('/');
        } catch (\Exception $e) {
            Session::flash('error', 'Une erreur est survenue lors de la réservation: ' . $e->getMessage());


            return $this->view('/', ['errors' => ['general' => $e->getMessage()]]);
        }
    }
}