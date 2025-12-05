<?php

namespace App\Controller;

use Exception;
use App\Entity\Post;
use App\Entity\Booking;
use App\Entity\Available;
use JulienLinard\Router\Request;
use JulienLinard\Router\Response;
use App\Repository\PostRepository;
use JulienLinard\Auth\AuthManager;
use App\Repository\BookingRepository;
use JulienLinard\Core\Session\Session;
use JulienLinard\Core\View\ViewHelper;
use App\Repository\AvailableRepository;
use JulienLinard\Doctrine\EntityManager;
use JulienLinard\Router\Attributes\Route;
use JulienLinard\Core\Controller\Controller;
use JulienLinard\Auth\Middleware\AuthMiddleware;

class BookingController extends Controller
{
    private PostRepository $postRepository;
    private BookingRepository $bookingRepository;


    public function __construct(
        private EntityManager $em,
        private AuthManager $auth
    ) {
        $this->postRepository = $this->em->createRepository(PostRepository::class, Post::class);
        $this->bookingRepository = $this->em->createRepository(BookingRepository::class, Booking::class);
    }

    #[Route("/booking/show", name: "app_booking_show", methods: ["GET"], middleware: [AuthMiddleware::class])]
    public function booking(): Response
    {
        $bookingRepo = $this->em->createRepository(BookingRepository::class, Booking::class);
        $bookings = $bookingRepo->findAll();

        
        foreach ($bookings as $booking) {
            $booking->post = $this->em->find(Post::class, $booking->post_id);
        }

        $user = $this->auth->user();

        return $this->view("booking/show", [
            "user" => $user,
            "bookings" => $bookings
        ]);
    }


    #[Route(path: "/booking/{id}/delete", name: "app_delete_booking", methods: ["POST"], middleware: [AuthMiddleware::class])]
public function deleteBooking(int $id): Response
{
    try {
        $user = $this->auth->user();

        // vérifie que la réservation existe et appartient à l'utilisateur
        $booking = $this->bookingRepository->findByIdAndUserId($id, $user->id);
        if (!$booking) {
            Session::flash("Réservation non trouvée ou accès refusé", ["booking_id" => $id, "user_id" => $user->id]);
            return $this->redirect("/dashboard"); 
        }

        // supp la réservation de la BDD
        $this->em->remove($booking);
        $this->em->flush();

        Session::flash("Réservation supprimée avec succès", ["booking_id" => $id, "user_id" => $user->id]);
        return $this->redirect("/booking/show"); 
    } catch (Exception $e) {
        Session::flash("Erreur lors de la suppression de la réservation", ["booking_id" => $id, "action" => "remove_booking", "error" => $e->getMessage()]);
        return $this->redirect("/booking/show"); 
    }
    }
}
