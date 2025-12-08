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

        <form method="POST" action="/hote/<?= htmlspecialchars($post->id) ?>/edit" enctype="multipart/form-data" class="space-y-6 ">
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
                    value="<?= htmlspecialchars($post->title) ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?= isset($errors['title']) ? 'border-red-500' : '' ?>"
                    required
                    maxlength="255">
                <?php if (isset($error)): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg flex">
                        <p class="text-sm text-red-700"><?= $error ?></p>
                    </div>
                <?php endif ?>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2" for="type_id">Type de logement</label>
                <select name="type_id" id="type_id" class=" w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="<?= htmlspecialchars($post->type_id) ?>"> Choisissez un type</option>

                    <?php foreach ($types as $type): ?>
                        <option value="<?= $type->id ?>">
                            <?= htmlspecialchars($type->label) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
                <?php if (!empty($errors['type_id'])): ?>
                    <p class="text-red-500 text-sm"><?= $errors['type_id'] ?></p>
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
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"><?= htmlspecialchars($post->description) ?></textarea>
            </div>
             <div>
                         <label for="media" class="block text-sm font-semibold text-gray-700 mb-2">
                        Fichier joint
                        </label>
                        <?php if($post->media_path):?>
                            <div class="mb-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4 ">
                                        <img class="h-20 w-20 object-cover rounded-lg border-2 border-gray-300" src="<?= htmlspecialchars($post->media_path) ?>" alt="Image actuelle">
                                        <div>
                                            <p class="text-sm font-medium text-gray-700">Image actuelle</p>
                                            <p class="text-xs text-gray-500">Cliquez sur l'image pour l'agrandir</p>
                                        </div>
                                    </div>
                                    <label class="flex items-center cursor-pointer">
                                        <input class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500" type="checkbox" name="remove_media" value="1">
                                        <span class="ml-2 text-sm text-red-600 font-medium">Supprimer</span>
                                    </label>
                                </div>
                            </div>


                        <?php endif?>



                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="media" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span> <?= $post->media_path ? 'Remplacer l\'image': 'Choisir une image' ?></span>
                                    <input id="media" name="media" type="file" class="sr-only" accept="image/jpeg,image/jpg,image/png,image/avif,image/webp">
                                </label>
                                <p class="pl-1">ou glisser-déposer</p>
                            </div>
                            <p class="text-xs text-gray-500">JPEG, JPG, PNG, AVIF, WEBP jusqu'à 10MB</p>
                        </div>
                        </div>
                </div>


            <div class="pt-4 ">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Équipements
                </label>

                <div class="max-h-80 overflow-y-auto border rounded-lg p-4 space-y-6 bg-white shadow-inner">
                    <?php foreach ($categorys as $category): ?>
                        <div>
                            <!-- Titre de catégorie -->
                            <div class="mt-4 mb-2">
                                <div class="text-sm font-medium text-gray-700 mb-1">
                                    <?= htmlspecialchars($category->label) ?>
                                </div>
                                <div class="border-b border-gray-200"></div>
                            </div>

                            <!-- Pills des équipements -->
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($equipments as $equipment): ?>
                                    <?php if ($equipment->category_id == $category->id): ?>
                                        <label class="cursor-pointer select-none">
                                            <input
                                                type="checkbox"
                                                name="equipment_ids[]"
                                                value="<?= $equipment->id ?>"
                                                class="peer hidden">
                                            <span class="
                                    px-3 py-1 rounded-full border border-gray-300 
                                    text-gray-700 text-sm 
                                    bg-gray-100
                                    peer-checked:bg-red-500 
                                    peer-checked:text-white 
                                    peer-checked:border-red-500
                                    transition
                                    hover:bg-red-400 hover:text-white
                                ">
                                                <?= htmlspecialchars($equipment->label) ?>
                                            </span>
                                        </label>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>






            <div>
                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                    Adresse
                </label>
                <input
                    id="address"
                    name="address"
                    value="<?= htmlspecialchars($post->address) ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>


            <div class="flex gap-5">
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                        Ville
                    </label>
                    <input
                        id="city"
                        name="city"
                       value="<?= htmlspecialchars($post->city) ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div>
                    <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                        Code postal
                    </label>
                    <input
                        id="postal_code"
                        name="postal_code"
                        value="<?= htmlspecialchars($post->postal_code) ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">

                </div>

                <div>
                    <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                        Pays
                    </label>
                    <input
                        id="country"
                        name="country"
                        value="<?= htmlspecialchars($post->country) ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

            </div>

            <div class="flex gap-5">
                <div class="flex-1">
                    <label for="date_in" class="block text-sm font-medium text-gray-700 mb-1">Debut de location</label>
                    <input type="date"  value="<?= $available ? htmlspecialchars($available->date_in->format('Y-m-d')) : '' ?>"  id="date_in" name="date_in" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>

                <div class="flex-1">
                    <label for="date_out" class="block text-sm font-medium text-gray-700 mb-1">Fin de location</label>
                    <input type="date" value="<?= $available ? htmlspecialchars($available->date_out->format('Y-m-d')) : '' ?>"  id="date_out" name="date_out" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            </div>



            <div class="flex gap-5">

                <div class="flex-1">
                    <label for="price_day" class="block text-sm font-medium text-gray-700 mb-2">
                        Prix par nuit
                    </label>
                    <input
                        id="price_day"
                        name="price_day"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        value="<?= htmlspecialchars($post->price_day) ?>">
                </div>

                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600 mb-2">Voyageurs</label>
                    <input
                        type="number"
                        name="max_capacity"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        min="1"
                        max="10"
                        value="<?= htmlspecialchars($post->max_capacity) ?>"
                        required>
                </div>

                <div class="flex-1">
                    <label class="text-sm font-medium text-gray-600 mb-2">Lit</label>
                    <input
                        type="number"
                        name="bed_count"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        min="1"
                        max="10"
                        value="<?= htmlspecialchars($post->bed_count) ?>"
                        required>
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