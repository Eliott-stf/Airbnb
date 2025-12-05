<main class="container mx-auto px-4 py-8">

    <div class="mb-8">
        <a href="/hote/create"
           class="inline-flex items-center px-6 py-3 bg-[#FF385C] hover:bg-[#E31C5F] hover:shadow-xl text-white rounded-xl font-semibold shadow-lg transition-all duration-300">
            Nouvelle annonce
        </a>
    </div>

     <h1 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Mes annonces (<?= count($posts) ?>)</h1>

    <?php if (!empty($posts)): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

            <?php foreach ($posts as $post): ?>
                <div class="bg-white rounded-xl shadow overflow-hidden group">

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

                        <p class="text-gray-600 text-sm mt-1 line-clamp-2">
                            <?= htmlspecialchars($post->description) ?>
                        </p>

                        <div class="flex mt-3 gap-2">
                           
                            <div class="flex items-center text-sm bg-gray-200 px-2 py-1 rounded-full gap-1">
                                <span><?= number_format($post->price_day, 0, '', ' ') ?>€ / nuit</span>
                            </div>
                          
                            <div class="flex items-center text-sm bg-gray-200 px-2 py-1 rounded-full gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M5.121 17.804A4 4 0 018 16h8a4 4 0 012.879 1.804M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                                </svg>
                                <span><?= $post->max_capacity ?> voyageur<?= $post->max_capacity > 1 ? 's' : '' ?></span>
                            </div>

                          
                            <div class="flex items-center text-sm bg-gray-200 px-2 py-1 rounded-full gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M2 19v-6q0-.675.275-1.225T3 10.8V8q0-1.25.875-2.125T6 5h4q.575 0 1.075.213T12 5.8q.425-.375.925-.587T14 5h4q1.25 0 2.125.875T21 8v2.8q.45.425.725.975T22 13v6h-2v-2H4v2zm11-9h6V8q0-.425-.288-.712T18 7h-4q-.425 0-.712.288T13 8zm-8 0h6V8q0-.425-.288-.712T10 7H6q-.425 0-.712.288T5 8zm-1 5h16v-2q0-.425-.288-.712T19 12H5q-.425 0-.712.288T4 13zm16 0H4z"/>
                                </svg>
                                <span><?= $post->bed_count ?> lit<?= $post->bed_count > 1 ? 's' : '' ?></span>
                            </div>

                        </div>

                      
                        <div class="mt-3 flex space-x-2">
                            <a href="/hote/<?= $post->id ?>/edit"
                               class="p-2 rounded-lg bg-[#FFE8EC] text-[#FF385C] hover:bg-[#FFD0D8]"
                               title="Modifier">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                             a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>

                            <form method="POST" action="/hote/<?= $post->id ?>/delete" onsubmit="return confirm('Supprimer cette annonce ?');">
                                <input type="hidden" name="_token" value="<?= htmlspecialchars(\JulienLinard\Core\View\ViewHelper::csrfToken()) ?>">
                                <button type="submit" class="p-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200" title="Supprimer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6
                                                 m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    <?php else: ?>
        <p class="text-center text-gray-500 text-lg mt-10">
            Vous n'avez aucune annonce pour le moment.
        </p>
    <?php endif; ?>

</main>
