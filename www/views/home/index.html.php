<?= \JulienLinard\Core\Middleware\CsrfMiddleware::field() ?>
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
            <div>
               <span class="relative inline-block group"><a href="/hote/index" class="relative z-10 px-4 py-2 text-sm font-medium text-gray-800">Devenir Hôte</a>
             <!-- Background qui apparaît au hover -->
            <span class="absolute inset-0 bg-gray-200 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
            </span>
            </div>


            <form action="/logout" method="POST" onsubmit="return confirm('Etes vous sur de vouloir vous déconnecter.')">
                            <?php use JulienLinard\Core\View\ViewHelper ?>
                            <input type="hidden" name="_token" value="<?= htmlspecialchars(ViewHelper::csrfToken())?>">
                            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                                Déconnexion
                            </button>
            </form>
 
            <div class="bg-[#f2f2f2] w-10 h-10 border-rad-50  rounded-full flex items-center justify-center">
               <a href="/" class="bg-[#f2f2f2] w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-200">
                 <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24">
                <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                  d="M12 21a9 9 0 1 0 0-18m0 18a9 9 0 1 1 0-18m0 18c2.761 0 3.941-5.163 3.941-9S14.761 3 12 3m0 18c-2.761 0-3.941-5.163-3.941-9S9.239 3 12 3M3.5 9h17m-17 6h17"/></svg>
                </a>
            </div>
 
            <div class="bg-[#f2f2f2] w-10 h-10 border-rad-50  rounded-full flex items-center justify-center">
                <a href="/" class="bg-[#f2f2f2] w-10 h-10 rounded-full flex items-center justify-center hover:bg-gray-200">
                 <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16">
                 <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" 
                    d="m2.75 12.25h10.5m-10.5-4h10.5m-10.5-4h10.5"/></svg>
                </a>
            </div>
    </div>
       
 
 
 
 
 
 
       
    </header>