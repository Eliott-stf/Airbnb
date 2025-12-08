<main class="container mx-auto px-4 py-8">


    <div class="mb-6">
        <a href="/" class="text-gray-500 hover:text-gray-800 text-sm">&larr; Retour aux logements</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">


        <div class="md:col-span-2">


            <div class="w-full h-80 rounded-xl overflow-hidden shadow mb-4">
                <div class="w-full h-full bg-gray-200 flex items-center justify-center text-gray-500 text-lg">
                    <img src="<?= htmlspecialchars($post->media_path) ?>">
                </div>
            </div>


            <div class="flex justify-between items-center mb-4">

                <h1 class="text-3xl font-bold text-gray-800"><?= htmlspecialchars($post->title) ?></h1>


                <div class="flex items-center bg-gray-200 rounded-full px-4 py-2 gap-2 text-gray-700 font-semibold">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M5.121 17.804A4 4 0 018 16h8a4 4 0 012.879 1.804M12 12a4 4 0 100-8 4 4 0 000 8z" />
                    </svg>
                    <span><?= $post->max_capacity ?> voyageur<?= $post->max_capacity > 1 ? 's' : '' ?></span>
                </div>
            </div>


            <p class="text-gray-600 text-lg mb-4">
                <?= htmlspecialchars($post->city) ?> (<?= htmlspecialchars($post->postal_code) ?>), <?= htmlspecialchars($post->country) ?>
            </p>


            <p class="text-gray-700 leading-relaxed mb-6"><?= nl2br(htmlspecialchars($post->description)) ?></p>


            <div class="flex gap-6">
                <div>
                    <span class="text-gray-500 text-sm">Nombre de lit</span>
                    <div class="text-gray-800 font-medium"><?= $post->bed_count ?></div>
                </div>

            </div>




            <div class="mt-6 p-4 bg-gray-100 rounded-lg">
                <h2 class="font-semibold text-gray-800 mb-2">Disponibilités</h2>
                <p class="text-gray-700">
                    Du <span class="font-medium"><?= $available->date_in->format('d/m/Y') ?></span>
                    au <span class="font-medium"><?= $available->date_out->format('d/m/Y') ?></span>
                </p>
            </div>


        </div>

        
       <?php if ($user->id != $post->user_id): ?>


            <div class="border rounded-xl p-6 shadow-lg h-fit sticky top-10">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-2xl font-semibold"><?= number_format($post->price_day, 0, '', ' ') ?> €/nuit</span>
                </div>

                <form method="POST" action="/post/<?= $post->id ?>">
                    <?= \JulienLinard\Core\Middleware\CsrfMiddleware::field() ?>
                    <div class="grid grid-cols-1 gap-4 mb-4">


                        <div class="grid grid-cols-3 gap-4">

                            <div>
                                <label class="text-sm font-medium text-gray-600">Arrivée</label>
                                <input
                                    type="date"
                                    name="date_in"
                                    class="w-full border rounded-lg px-3 py-2"
                                    <?php if ($available): ?>
                                    min="<?= $available->date_in->format('Y-m-d') ?>"
                                    max="<?= $available->date_out->format('Y-m-d') ?>"
                                    <?php endif; ?>
                                    required>
                            </div>


                            <div>
                                <label class="text-sm font-medium text-gray-600">Départ</label>
                                <input
                                    type="date"
                                    name="date_out"
                                    class="w-full border rounded-lg px-3 py-2"
                                    <?php if ($available): ?>
                                    min="<?= $available->date_in->format('Y-m-d') ?>"
                                    max="<?= $available->date_out->format('Y-m-d') ?>"
                                    <?php endif; ?>
                                    required>
                            </div>


                            <div>
                                <label class="text-sm font-medium text-gray-600">Voyageurs</label>
                                <input
                                    type="number"
                                    name="guest_count"
                                    class="w-full border rounded-lg px-3 py-2"
                                    min="1"
                                    max="<?= $post->max_capacity ?>"
                                    value="1"
                                    required>
                            </div>
                        </div>

                    </div>

                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-3 rounded-lg font-semibold">
                        Réserver
                    </button>
                </form>

                <p class="text-center text-gray-500 mt-4 text-sm">
                    Vous ne serez débité que si la réservation est acceptée.
                </p>
            </div>
        <?php endif; ?>


    </div>
</main>