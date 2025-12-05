<?php

/**
 * ============================================
 * BOOKING REPOSITORY
 * ============================================
 * 
 * Repository personnalisé pour l'entité booking
 * Ajoute des méthodes spécifiques pour la recherche d'utilisateurs
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Booking;
use JulienLinard\Doctrine\Repository\EntityRepository;

class BookingRepository extends EntityRepository
{
    /**
     * Récupère toutes les réservations d'un utilisateur grâce à son ID
     * @param int $userId
     * @param array|null $orderBy Ordre de tri
     * @return array Liste des bookings
     */
    public function findByUserId(int $userId, ?array $orderBy = null): array
    {
        return $this->findBy(
            ["user_id" => $userId],
            $orderBy ?? ["created_at" => "DESC"]
        );
    }

    /**
     * Récupère une réservation grâce à son ID et qui appartient à un utilisateur spécifique
     * @param int $id ID de la réservation
     * @param int $userId ID de l'utilisateur
     * @return Booking|null Retourne l'objet Booking ou null si non trouvé ou pas accès
     */
    public function findByIdAndUserId(int $id, int $userId): ?Booking
    {
        $booking = $this->find($id);

        if (!$booking || $booking->user_id !== $userId) {
            return null;
        }

        return $booking;
    }
}

