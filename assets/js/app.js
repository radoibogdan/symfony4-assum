/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.scss in this case)
import '../css/app.scss';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

// IMPORTER LE JS DU EDITEUR DE MARKDOWN
import SimpleMDE from "simplemde";

// CHARGER LE CSS
require('simplemde/dist/simplemde.min.css');

// CONFIGURER L'EDITEUR SIMPLE MDE
document.addEventListener('DOMContentLoaded', function () {
    let textarea = document.getElementById('slide_content');
    let editor_name = 'slide_' + textarea.dataset.slideId;

    var simplemde = new SimpleMDE({
        autosave: {
            enabled: true,
            uniqueId: editor_name
        },
        element: textarea,
        forceSync: true,
        spellChecker: false,
        tabSize: 4
    });
});
