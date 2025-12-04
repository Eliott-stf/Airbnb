<?= \JulienLinard\Core\Middleware\CsrfMiddleware::field() ?>

<header class="bg-[#fbfbfb] p-4 flex justify-between items-center shadow-xl">

    <div class="w-24 h-34">
        <a href="/">
            <img src="/assets/img/Airbnb_large.png" alt="Logo">
        </a>
    </div>



    <div
        class="bg-white px-3 py-1 rounded-[35px] space-x-5 shadow-[0_0_10px_-2px_rgba(0,0,0,0.75)] flex items-center justify-center">
        <p>NAVIGATION</p>

        <button class="bg-[#ff5a5f] mx-2 w-10 h-10 rounded-full flex items-center justify-center">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                <path fill="white"
                    d="M9.5 16q-2.725 0-4.612-1.888T3 9.5t1.888-4.612T9.5 3t4.613 1.888T16 9.5q0 1.1-.35 2.075T14.7 13.3l5.6 5.6q.275.275.275.7t-.275.7t-.7.275t-.7-.275l-5.6-5.6q-.75.6-1.725.95T9.5 16m0-2q1.875 0 3.188-1.312T14 9.5t-1.312-3.187T9.5 5T6.313 6.313T5 9.5t1.313 3.188T9.5 14" />
            </svg>
        </button>
    </div>



    <div class="flex items-center space-x-4">
        <div>
            <span class="relative inline-block group"><a href="/hote/index"
                    class="relative z-10 px-4 py-2 text-sm font-medium text-gray-800">Devenir Hôte</a>
                <!-- Background qui apparaît au hover -->
                <span
                    class="absolute inset-0 bg-gray-200 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
            </span>
        </div>


        <form action="/logout" method="POST" onsubmit="return confirm('Etes vous sur de vouloir vous déconnecter.')">
            <?php use JulienLinard\Core\View\ViewHelper ?>
            <input type="hidden" name="_token" value="<?= htmlspecialchars(ViewHelper::csrfToken()) ?>">
            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                Déconnexion
            </button>
        </form>

        <div class="bg-[#f2f2f2] w-10 h-10 border-rad-50  rounded-full flex items-center justify-center">
            <a href="/" class="bg-[#f2f2f2] w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-200">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M12 21a9 9 0 1 0 0-18m0 18a9 9 0 1 1 0-18m0 18c2.761 0 3.941-5.163 3.941-9S14.761 3 12 3m0 18c-2.761 0-3.941-5.163-3.941-9S9.239 3 12 3M3.5 9h17m-17 6h17" />
                </svg>
            </a>
        </div>

        <div class="bg-[#f2f2f2] w-10 h-10 border-rad-50  rounded-full flex items-center justify-center">
            <a href="/" class="bg-[#f2f2f2] w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-200">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16">
                    <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                        stroke-width="1.5" d="m2.75 12.25h10.5m-10.5-4h10.5m-10.5-4h10.5" />
                </svg>
            </a>
        </div>
    </div>








</header>

<main class="container mx-auto px-4 py-8">

    <section class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-2">Logements Populaires ></h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-x-6 gap-y-10">

            <?php foreach ($posts as $post): ?>

                <a href="/post/<?= htmlspecialchars($post->id) ?>" class="destination-card block group cursor-pointer">
                    <div class="w-full">

                        <!-- Conteneur d'image avec étiquette Guestfavorite -->
                        <div class="relative overflow-hidden mb-3">
                            <!-- Placeholder d'image (ajoutez une vraie image si disponible) -->
                            <div
                                class="w-full h-60 bg-gray-200 rounded-xl overflow-hidden group-hover:scale-105 transition-transform duration-500 ease-in-out">
                                <div class="flex items-center justify-center h-full text-gray-500">
                                    [Image du post : <?= htmlspecialchars($post->city) ?>]
                                </div>
                            </div>

                            <!-- Balise Guestfavorite -->
                            <?php if ($post->max_capacity > 6): // Exemple de condition ?>
                                <p
                                    class="absolute top-3 left-3 bg-white text-xs font-semibold px-2 py-1 rounded-full shadow-md">
                                    Guestfavorite
                                </p>
                            <?php endif; ?>
                        </div>

                        <!-- Détails du Post -->
                        <div class="destination-text-container text-sm">

                            <!-- Ville et Note -->
                            <div
                                class="flex justify-between items-start destination-text-header font-semibold text-gray-800">
                                <span class="truncate pr-2">
                                    <?= htmlspecialchars($post->city) ?>, <?= htmlspecialchars($post->country) ?>
                                </span>
                                <!-- Symbole d'étoile (Note fictive pour le moment) -->
                                <span class="flex items-center text-xs">
                                    <svg class="w-3 h-3 text-black mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.96a1 1 0 00.95.69h4.167c.969 0 1.371 1.24.588 1.81l-3.375 2.45c-.407.295-.595.83-.435 1.343l1.286 3.96c.3.921-.755 1.688-1.542 1.118l-3.375-2.45a1 1 0 00-1.07 0l-3.375 2.45c-.787.57-1.842-.197-1.542-1.118l1.286-3.96c.16-.513-.028-1.048-.435-1.343l-3.375-2.45c-.783-.57-.381-1.81.588-1.81h4.167a1 1 0 00.95-.69l1.286-3.96z" />
                                    </svg>
                                    4.8 (Ex)
                                </span>
                            </div>

                            <!-- Description courte (Titre du post ou type) -->
                            <p class="text-gray-500 text-sm mt-1 truncate">
                                <?= htmlspecialchars($post->title) ?>
                            </p>

                            <!-- Prix -->
                            <p class="text-sm font-medium mt-1">
                                <span class="font-bold">
                                    <?= number_format($post->price_day, 0, '', ' ') ?> €
                                </span>
                                <span class="text-gray-600">
                                    par nuit
                                </span>
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