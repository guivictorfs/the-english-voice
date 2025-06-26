import "./bootstrap";
import Filter from "bad-words/lib/filter";

// Filtro de palavras ofensivas
const filter = new Filter();

document.addEventListener('DOMContentLoaded', function() {
    // Formulário de artigo (texto)
    const formArtigo = document.getElementById('form-artigo');
    if(formArtigo) {
        formArtigo.addEventListener('submit', function(e) {
            const titulo = formArtigo.querySelector('[name="titulo"]').value || '';
            const conteudo = window.quill ? window.quill.root.innerText : '';
            const keywords = formArtigo.querySelector('[name="keywords"]').value || '';
            if(filter.isProfane(titulo) || filter.isProfane(conteudo) || filter.isProfane(keywords)) {
                alert('Seu artigo contém palavras inadequadas. Remova para poder enviar.');
                e.preventDefault();
                return false;
            }
        });
    }
    // Formulário de PDF
    const formPdf = document.getElementById('form-pdf');
    if(formPdf) {
        formPdf.addEventListener('submit', function(e) {
            const titulo = formPdf.querySelector('[name="titulo"]').value || '';
            const keywords = formPdf.querySelector('[name="keywords"]').value || '';
            // Não é possível filtrar o conteúdo do PDF no frontend
            if(filter.isProfane(titulo) || filter.isProfane(keywords)) {
                alert('Seu artigo contém palavras inadequadas. Remova para poder enviar.');
                e.preventDefault();
                return false;
            }
        });
    }
});
