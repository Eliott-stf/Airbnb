<main class="container mx-auto px-4 py-8">

    <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Mes réservations ></h2>

    <?php if (!empty($bookings)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

            <?php foreach ($bookings as $booking):
                $post = $booking->post;
            ?>
                <div class="bg-white rounded-xl shadow overflow-hidden group">
                <a href="/post/<?= htmlspecialchars($post->id) ?>" class="destination-card block group cursor-pointer">
                    <div class="relative w-full h-48 overflow-hidden">
                        <?php if (!empty($post->media_path)): ?>
                            <img
                                src="<?= htmlspecialchars($post->media_path) ?>"
                                alt="<?= htmlspecialchars($post->title) ?>"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-in-out"
                            >
                        <?php else: ?>
                            <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500">
                                Aucune image
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800 truncate">
                            <?= htmlspecialchars($post->title) ?>
                        </h3>

                        <p class="text-gray-600 text-sm mt-1">
                            Du <span class="font-medium"><?= $booking->date_in->format('d/m/Y') ?></span>
                            au <span class="font-medium"><?= $booking->date_out->format('d/m/Y') ?></span>
                        </p>

                        <div class="flex items-center mt-2">
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5 text-gray-600 mr-1"
                                 fill="none"
                                 viewBox="0 0 24 24"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M5.121 17.804A4 4 0 018 16h8a4 4 0 012.879 1.804M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                            </svg>
                            <span class="text-gray-700 text-sm font-medium">
                                <?= $booking->guest_count ?> voyageur<?= $booking->guest_count > 1 ? 's' : '' ?>
                            </span>
                        </div>

                    
                        <form method="POST" action="/booking/<?= $booking->id ?>/delete" 
                              class="mt-4"
                              onsubmit="return confirm('Voulez-vous vraiment supprimer cette réservation ?');">
                            <input type="hidden" name="_token" value="<?= htmlspecialchars(\JulienLinard\Core\View\ViewHelper::csrfToken()) ?>">
                            <button type="submit" 
                                    class="w-full flex items-center justify-center p-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200 font-semibold">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
                                             m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Supprimer
                            </button>
                        </form>

                    </div>
                </a>
                </div>
            <?php endforeach; ?>

        </div>
    <?php else: ?>

        <p class="text-center text-gray-500 text-lg mt-10">
            Vous n'avez aucune réservation pour le moment.
        </p>

    <?php endif; ?>

</main>
