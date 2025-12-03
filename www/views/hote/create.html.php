<?php
// Initialiser les variables si elles n'existent pas
$errors = $errors ?? [];
$old = $old ?? ['title' => '', 'description' => ''];
?>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-lg p-8 ">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Ajouter un bien</h1>

        <?php if (isset($errors['general'])): ?>
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-600"><?= htmlspecialchars($errors['general']) ?></p>
            </div>
        <?php endif; ?>

        <form method="POST" action="/hote" enctype="multipart/form-data" class="space-y-6 ">
            <?= \JulienLinard\Core\Middleware\CsrfMiddleware::field() ?>

            <!-- Title -->
            <div class=" pt-4 border-t border-gray-200">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    Titre
                </label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    value="<?= htmlspecialchars($old['title'] ?? '') ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?= isset($errors['title']) ? 'border-red-500' : '' ?>"
                    required
                    maxlength="255">
                <?php if (isset($errors['title'])): ?>
                    <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['title']) ?></p>
                <?php endif; ?>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    Description
                </label>
                <textarea
                    id="description"
                    name="description"
                    rows="4"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($old['description'] ?? '') ?></textarea>
            </div>


            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                    Adresse
                </label>
                <input
                    id="address"
                    name="address"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($old['address'] ?? '') ?></textarea>
            </div>


            <div class="flex gap-5">
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                        Ville
                    </label>
                    <input
                        id="city"
                        name="city"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($old['city'] ?? '') ?></textarea>
                </div>

                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                        Code postal
                    </label>
                    <input
                        id="postal_code"
                        name="postal_code"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($old['postal_code'] ?? '') ?>
                </div>

                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                        Pays
                    </label>
                    <input
                        id="country"
                        name="country"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($old['country'] ?? '') ?>
                </div>

            </div>

            <div>
                <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                    DATE DE SEJOUR TODO....
                </label>
                <input
                    id="country"
                    name="country"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($old['country'] ?? '') ?>
            </div>


            <div class="flex gap-5">

                <div>
                    <label for="price_day" class="block text-sm font-medium text-gray-700 mb-2">
                        Prix par nuit
                    </label>
                    <input
                        id="price_day"
                        name="price_day"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($old['price_day'] ?? '') ?>
                </div>

                <div>

                    <label for="max_capacity" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de personne
                    </label>
                    <input
                        id="max_capacity"
                        name="max_capacity"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($old['max_capacity'] ?? '') ?>
                </div>

                <div>
                    <label for="bed_count" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de lit
                    </label>
                    <input
                        id="bed_count"
                        name="bed_count"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($old['bed_count'] ?? '') ?>
                </div>


            </div>


            <!-- Buttons -->
            <div class="flex gap-4">
                <button
                    type="submit"
                    class="flex-1 bg-[#ff385c] text-white py-2 px-4 rounded-lg hover:bg-[#ff385f] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
                    Suivant
                </button>
                <a
                    href="/"
                    class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-150">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>