<main class="container mx-auto px-4 py-8">
    <section class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Logements Populaires ></h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-6 gap-y-10">

            <?php foreach ($posts as $post): ?>
                <a href="/post/<?= htmlspecialchars($post->id) ?>" class="destination-card block group cursor-pointer">
                    <div class="w-full">

                        <div class="relative overflow-hidden mb-3">
                            <?php if (!empty($post->media_path)): ?>
                                <img
                                    src="<?= htmlspecialchars($post->media_path) ?>"
                                    alt="Image du logement"
                                    class="w-full h-60 object-cover rounded-xl group-hover:scale-105 transition-transform duration-500 ease-in-out"
                                >
                            <?php else: ?>
                                <div class="w-full h-60 bg-gray-200 rounded-xl flex items-center justify-center text-gray-500">
                                    Aucune image
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="destination-text-container text-sm">
                            <div class="flex justify-between items-start font-semibold text-gray-800">
                                <span class="truncate pr-2">
                                    <?= htmlspecialchars($post->city) ?>, <?= htmlspecialchars($post->country) ?>
                                </span>
                            </div>

                            <p class="text-gray-500 text-sm mt-1 truncate">
                                <?= htmlspecialchars($post->title) ?>
                            </p>

                            <p class="text-sm font-medium mt-1">
                                <span class="font-bold">
                                    <?= number_format($post->price_day, 0, '', ' ') ?> €
                                </span>
                                <span class="text-gray-600">par nuit</span>
                            </p>
                        </div>

                    </div>
                </a>
            <?php endforeach; ?>

            <?php if (empty($posts)): ?>
                <p class="col-span-full text-center text-gray-500 text-lg mt-10">
                    Aucun logement disponible pour l'instant.
                </p>
            <?php endif; ?>

        </div>
    </section>
</main>
