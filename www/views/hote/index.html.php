<div class="min-h-screen bg-gradient-to-br from-[#FFEBEF] via-white to-[#FFE5EA]">
   <header class="bg-[#fbfbfb] p-4 flex justify-between items-center">
 
         <div class="w-24 h-34">
            <a href="/">
            <img src="/assets/img/Airbnb_large.png" alt="Logo">
            </a>
        </div>
 
 
 
        <div class="bg-white px-3 py-1 rounded-[35px] space-x-5 shadow-[0_0_10px_-2px_rgba(0,0,0,0.75)] flex items-center justify-center">
            <p>NAVIGATION</p>
 
            <button class="bg-[#ff385c] mx-2 w-10 h-10 rounded-full flex items-center justify-center">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="white" d="M9.5 16q-2.725 0-4.612-1.888T3 9.5t1.888-4.612T9.5 3t4.613 1.888T16 9.5q0 1.1-.35 2.075T14.7 13.3l5.6 5.6q.275.275.275.7t-.275.7t-.7.275t-.7-.275l-5.6-5.6q-.75.6-1.725.95T9.5 16m0-2q1.875 0 3.188-1.312T14 9.5t-1.312-3.187T9.5 5T6.313 6.313T5 9.5t1.313 3.188T9.5 14"/></svg>
            </button>
        </div>
 
 
 
        <div class="flex items-center space-x-4">
           <p class="text-sm text-gray-600">
                        Bonjour, <span class="font-semibold text-gray-900"><?= htmlspecialchars($user->firstname ?? $user->email)  ?></span> ! </p>


            <form action="/logout" method="POST" onsubmit="return confirm('Etes vous sur de vouloir vous déconnecter.')">
                            <?php use JulienLinard\Core\View\ViewHelper ?>
                            <input type="hidden" name="_token" value="<?= htmlspecialchars(ViewHelper::csrfToken())?>">
                            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                                Déconnexion
                            </button>
            </form>
 
            <div class="bg-[#f2f2f2] w-10 h-10 border-rad-50  rounded-full flex items-center justify-center">
                <p><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21a9 9 0 1 0 0-18m0 18a9 9 0 1 1 0-18m0 18c2.761 0 3.941-5.163 3.941-9S14.761 3 12 3m0 18c-2.761 0-3.941-5.163-3.941-9S9.239 3 12 3M3.5 9h17m-17 6h17"/></svg></p>
            </div>
 
            <div class="bg-[#f2f2f2] w-10 h-10 border-rad-50  rounded-full flex items-center justify-center">
                <p><svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="m2.75 12.25h10.5m-10.5-4h10.5m-10.5-4h10.5"/></svg></p>
            </div>
        </div>
       
 
 
 
 
 
 
       
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

        <!-- Bouton créer une annonce -->
        <div class="mb-8">
            <a href="/hote/create"
               class="inline-flex items-center px-6 py-3 bg-[#FF385C] hover:bg-[#E31C5F] hover:shadow-xl text-white rounded-xl font-semibold shadow-lg transition-all duration-300">
                Nouvelle annonce
            </a>
        </div>

        <!-- Liste des annonces -->
        <div class="bg-white rounded-xl shadow-xl overflow-hidden">

            <div class="bg-gradient-to-r from-[#FF385C] to-[#E31C5F] px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center gap-2">
                    Mes annonces (<?= count($posts) ?>)
                    <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em"
                         viewBox="0 0 24 24" fill="currentColor" class="text-white">
                        <path d="M12 3l9.5 7.5-1.8 1.5L12 6 4.3 12l-1.8-1.5z" />
                        <path d="M5 13h14v8H5z" />
                    </svg>
                </h2>
            </div>

            <div class="divide-y divide-gray-100">

                <?php if (empty($posts)): ?>
                    <div class="px-6 py-16 text-center">
                        <h3 class="text-lg font-semibold text-gray-900">Aucune annonce publiée</h3>
                        <p class="mt-2 text-sm text-gray-500">Créez votre première annonce Airbnb !</p>
                        <div class="mt-6">
                            <a href="/hote/create"
                               class="inline-flex items-center px-6 py-3 bg-[#FF385C] hover:bg-[#E31C5F] hover:shadow-xl text-white rounded-xl font-semibold shadow-lg transition-all">
                                Créer une annonce
                            </a>
                        </div>
                    </div>
                <?php else: ?>

                    <?php foreach ($posts as $post): ?>
                        <div class="px-6 py-5 hover:bg-gray-50 transition-colors duration-150">
                            <div class="flex items-start space-x-6">

                                <!-- Image -->
                                <?php if (!empty($post->image_path)): ?>
                                    <img src="<?= htmlspecialchars($post->image_path) ?>"
                                         class="w-32 h-24 rounded-lg object-cover shadow-md border"
                                         alt="Image de l'annonce">
                                <?php else: ?>
                                    <div class="w-32 h-24 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
                                        <svg width="32" height="32" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                  d="M12 3l9.5 7.5-1.8 1.5L12 6 4.3 12l-1.8-1.5zM5 13h14v8H5z"/>
                                        </svg>
                                    </div>
                                <?php endif; ?>

                                <!-- Infos -->
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h3 class="text-xl font-semibold text-gray-900">
                                                <?= htmlspecialchars($post->title) ?>
                                            </h3>

                                            <p class="mt-1 text-sm text-gray-600 line-clamp-2">
                                                <?= htmlspecialchars($post->description) ?>
                                            </p>

                                            <div class="mt-3 flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                                <span>
                                                    <strong class="mr-1 text-gray-900"><?= $post->price_day ?>€</strong>/ nuit
                                                </span>

                                                <span><?= $post->bed_count ?> lit(s)</span>

                                                <span><?= $post->max_capacity ?> voyageurs max</span>

                                                <span><?= htmlspecialchars($post->city) ?> (<?= $post->postal_code ?>), <?= htmlspecialchars($post->country) ?></span>

                                                <span class="text-xs text-gray-500">
                                                    Publié le <?= $post->created_at ? $post->created_at->format('d/m/Y') : '' ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center space-x-2">
                                    <a href="/hote/<?= $post->id ?>/edit"
                                       class="p-2 rounded-lg bg-[#FFE8EC] text-[#FF385C] hover:bg-[#FFD0D8]"
                                       title="Modifier">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414
                                                  a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>

                                    <form method="POST" action="/hote/<?= $post->id ?>/delete"
                                          onsubmit="return confirm('Supprimer cette annonce ?');">
                                        <input type="hidden" name="_token"
                                               value="<?= htmlspecialchars(ViewHelper::csrfToken()) ?>">
                                        <button type="submit"
                                                class="p-2 rounded-lg bg-red-100 text-red-700 hover:bg-red-200"
                                                title="Supprimer">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                 viewBox="0 0 24 24">
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

                <?php endif; ?>
            </div>

        </div>
    </main>
</div>
