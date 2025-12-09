<main class="container mx-auto px-4 py-8">

    <div class="mb-6">
        <a href="/" class="text-gray-500 hover:text-gray-800 text-sm">&larr; Retour aux logements</a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <div class="md:col-span-2">

            <div class="w-full h-96 rounded-xl overflow-hidden shadow-lg mb-6">
                <img src="<?= htmlspecialchars($post->media_path) ?>" alt="<?= htmlspecialchars($post->title) ?>" class="w-full h-full object-cover">
            </div>

            <div class="flex flex-col mb-4 border-b pb-4">
                <h1 class="text-3xl font-bold text-gray-800 mb-2"><?= htmlspecialchars($post->title) ?></h1>
                
                <div class="flex items-center justify-between text-gray-600">
                    <p class="text-lg">
                        <?= htmlspecialchars($post->city) ?> (<?= htmlspecialchars($post->postal_code) ?>), <?= htmlspecialchars($post->country) ?>
                    </p>
                    <div class="flex items-center bg-gray-100 rounded-full px-3 py-1 gap-2 text-sm font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.121 17.804A4 4 0 018 16h8a4 4 0 012.879 1.804M12 12a4 4 0 100-8 4 4 0 000 8z" />
                        </svg>
                        <span><?= $post->max_capacity ?> voyageur<?= $post->max_capacity > 1 ? 's' : '' ?></span>
                    </div>
                </div>
            </div>

            <h2 class="text-xl font-semibold text-gray-800 mb-3">Description du logement</h2>
            <p class="text-gray-700 leading-relaxed mb-8"><?= nl2br(htmlspecialchars($post->description)) ?></p>

            <div class="flex flex-wrap gap-x-10 gap-y-4 mb-8 p-4 border rounded-lg bg-gray-50">
                <div>
                    <span class="text-gray-500 text-sm block">Nombre de lits</span>
                    <div class="text-gray-800 font-medium text-lg"><?= $post->bed_count ?></div>
                </div>
                <div>
                    <span class="text-gray-500 text-sm block">Type de logement</span>
                    <div class="text-gray-800 font-medium text-lg"><?= htmlspecialchars($type ? $type->label : 'Non spécifié') ?></div>
                </div>
                <div>
                    <span class="text-gray-500 text-sm block">Période de location</span>
                    <div class="text-gray-800 font-medium text-lg">
                        Du <?= $available->date_in->format('d/m/Y') ?> au <?= $available->date_out->format('d/m/Y') ?>
                    </div>
                </div>
            </div>

            <div class="mt-6 border-t pt-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Équipements inclus</h3>

                <?php if (!empty($equipments)): ?>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($equipments as $equipment): ?>
                            <span class="
                                 px-3 py-1 rounded-full border border-red-500 
                                text-red-500 text-sm 
                                bg-white 
                                shadow-sm">
                                <?= htmlspecialchars($equipment->label) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">Aucun équipement spécifié pour ce logement.</p>
                <?php endif; ?>
            </div>



    <?php if ($user && $post->user_id !== $user->getAuthIdentifier()): ?>
        </div> <div class="md:col-span-1">
            <div class="border rounded-xl p-6 shadow-xl sticky top-6 bg-white">
                <div class="flex items-center justify-between mb-4 border-b pb-4">
                    <span class="text-2xl font-bold text-gray-800"><?= number_format($post->price_day, 0, '', ' ') ?> €</span>
                    <span class="text-gray-600">/ nuit</span>
                </div>



       
                <form method="POST" action="/post/<?= $post->id ?>">
                    <?= \JulienLinard\Core\Middleware\CsrfMiddleware::field() ?>
                    
                    <div class="grid grid-cols-2 gap-3 mb-4 border rounded-lg divide-x divide-gray-200">
                        <div class="p-3">
                            <label class="text-xs font-medium text-gray-600 block mb-1">ARRIVÉE</label>
                            <input
                                type="date"
                                name="date_in"
                                class="w-full text-sm focus:outline-none bg-transparent"
                                <?php if ($available): ?>
                                min="<?= $available->date_in->format('Y-m-d') ?>"
                                max="<?= $available->date_out->format('Y-m-d') ?>"
                                <?php endif; ?>
                                required>
                        </div>
                        <div class="p-3">
                            <label class="text-xs font-medium text-gray-600 block mb-1">DÉPART</label>
                            <input
                                type="date"
                                name="date_out"
                                class="w-full text-sm focus:outline-none bg-transparent"
                                <?php if ($available): ?>
                                min="<?= $available->date_in->format('Y-m-d') ?>"
                                max="<?= $available->date_out->format('Y-m-d') ?>"
                                <?php endif; ?>
                                required>
                        </div>
                    </div>
                    
                    <div class="mb-6 border rounded-lg p-3">
                         <label class="text-xs font-medium text-gray-600 block mb-1">VOYAGEURS</label>
                         <input
                            type="number"
                            name="guest_count"
                            class="w-full text-sm focus:outline-none bg-transparent"
                            min="1"
                            max="<?= $post->max_capacity ?>"
                            value="1"
                            required>
                    </div>

                    <button type="submit" class="w-full bg-red-500 hover:bg-red-600 text-white py-3 rounded-lg font-semibold transition duration-150">
                        Réserver
                    </button>
                </form>

                <p class="text-center text-gray-500 mt-4 text-sm">
                    Vous ne serez débité que si la réservation est acceptée.
                </p>
            </div>
        </div> 
        <?php endif; ?>
    </div>
 </main>