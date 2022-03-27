document.querySelector('form')
    .addEventListener('submit', () => {
        const button = document.querySelector('button')

        button.classList.add('disabled')
        button.innerText = 'Един момент...'
    })