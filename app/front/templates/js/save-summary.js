document.querySelector('textarea[name="summary"]').addEventListener('keydown', (event) => {
    localStorage.setItem(
        `summary_for_${event.target.parentElement.parentElement.parentElement.dataset.slug}`,
        event.target.value
    );

});

document.querySelector('form#edit').addEventListener('submit', function (event) {
    localStorage.removeItem(`summary_for_${event.target.dataset.slug}`);
});

//on page load, check if there is a summary in local storage for this page
//if there is, populate the summary textarea with it
const slug = document.querySelector('form#edit').dataset.slug;
const summary = localStorage.getItem(`summary_for_${slug}`);
if (summary) {
    const textarea = document.querySelector('textarea[name="summary"]');
    textarea.value = summary;
    textarea.classList.add('unsaved');
}
