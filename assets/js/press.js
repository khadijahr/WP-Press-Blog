// (function($) {
//     $(document).ready(function() {
//         const articles = document.querySelectorAll('.kmXVUK');

//         articles.forEach(article => {
//             article.addEventListener('mouseover', function() {
//                 const title = this.getAttribute('data-title');
//                 const image = this.getAttribute('data-image');
//                 const content = this.getAttribute('data-content');
//                 const link = this.getAttribute('data-link');

//                 const displayTitle = document.querySelector('.cmKIsl .styles__Title-sc-1nfpysv-1');
//                 const displayDescription = document.querySelector('.cmKIsl .styles__SubTitle-sc-1nfpysv-3');
//                 const displayLink = document.querySelector('.cmKIsl a');

//                 displayTitle.textContent = title;
//                 displayDescription.textContent = content;
//                 displayLink.setAttribute('href', link);

//                 // Mettre l'image en arrière-plan
//                 document.querySelector('.styles__ImageWrapper-sc-1wbeypc-2').style.backgroundImage = `url(${image})`;
//             });

//             article.addEventListener('mouseout', function() {
//                 const displayTitle = document.querySelector('.cmKIsl .styles__Title-sc-1nfpysv-1');
//                 const displayDescription = document.querySelector('.cmKIsl .styles__SubTitle-sc-1nfpysv-3');

//                 displayTitle.textContent = 'Survolez un article pour voir le titre ici';
//                 displayDescription.textContent = '';

//                 // Réinitialiser l'image en arrière-plan
//                 document.querySelector('.styles__ImageWrapper-sc-1wbeypc-2').style.backgroundImage = 'none';
//             });
//         });
//     });
// })(jQuery);

(function($) {
    $(document).ready(function() {
        // Variables pour stocker les informations de l'article actuellement survolé
        let currentTitle = '';
        let currentImage = '';
        let currentContent = '';
        let currentLink = '';

        // Sélection des éléments à surveiller (les articles)
        const articles = document.querySelectorAll('.kmXVUK');

        // Sélection de la div où afficher les informations de l'article
        const displayTitle = document.querySelector('.cmKIsl .styles__Title-sc-1nfpysv-1');
        const displayDescription = document.querySelector('.cmKIsl .styles__SubTitle-sc-1nfpysv-3');
        const displayLink = document.querySelector('.cmKIsl a');

        // Fonction pour mettre à jour les informations affichées dans la div cmKIsl
        function updateDisplayedInfo() {
            displayTitle.textContent = currentTitle;
            displayDescription.textContent = currentContent;
            displayLink.setAttribute('href', currentLink);

            // Mettre l'image en arrière-plan
            if (currentImage) {
                document.querySelector('.styles__ImageWrapper-sc-1wbeypc-2').style.backgroundImage = `linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url(${currentImage})`;
            } else {
                document.querySelector('.styles__ImageWrapper-sc-1wbeypc-2').style.backgroundImage = 'none';
            }
        }

        // Initialisation avec le premier article
        if (articles.length > 0) {
            const firstArticle = articles[0];
            currentTitle = firstArticle.getAttribute('data-title');
            currentImage = firstArticle.getAttribute('data-image');
            currentContent = firstArticle.getAttribute('data-content');
            currentLink = firstArticle.getAttribute('data-link');
            updateDisplayedInfo();
        }

        // Survol d'un article
        articles.forEach(article => {
            article.addEventListener('mouseover', function() {
                // Mettre à jour les variables avec les données de l'article survolé
                currentTitle = this.getAttribute('data-title');
                currentImage = this.getAttribute('data-image');
                currentContent = this.getAttribute('data-content');
                currentLink = this.getAttribute('data-link');

                // Mettre à jour l'affichage
                updateDisplayedInfo();
            });
        });

        // Survol de la div cmKIsl (pour garder les informations affichées)
        document.querySelector('.cmKIsl').addEventListener('mouseover', function() {
            // Mettre à jour l'affichage avec les dernières informations connues
            updateDisplayedInfo();
        });

        // Retour à l'état initial lorsque le curseur quitte la zone
        document.querySelector('.kmXVUK-wrapper').addEventListener('mouseout', function() {
            // Réinitialiser les variables
            currentTitle = '';
            currentImage = '';
            currentContent = '';
            currentLink = '';

            // Réinitialiser l'affichage dans cmKIsl
            displayTitle.textContent = 'Survolez un article pour voir le titre ici';
            displayDescription.textContent = '';
            displayLink.setAttribute('href', '');

            // Réinitialiser l'image en arrière-plan
            document.querySelector('.styles__ImageWrapper-sc-1wbeypc-2').style.backgroundImage = 'none';

            // Réinitialisation avec le premier article
            if (articles.length > 0) {
                const firstArticle = articles[0];
                currentTitle = firstArticle.getAttribute('data-title');
                currentImage = firstArticle.getAttribute('data-image');
                currentContent = firstArticle.getAttribute('data-content');
                currentLink = firstArticle.getAttribute('data-link');
            }
        });
    });
})(jQuery);

