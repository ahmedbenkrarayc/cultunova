const form = document.getElementById('form')

form.addEventListener('submit', (e) => {
    e.preventDefault()
    let title = document.getElementById('title').value
    let cover = document.getElementById('cover').value
    let category = document.getElementById('category').value
    let description = document.getElementById('description').value
    let content = document.getElementById('content').value

    let formInputs = [
        { type: "string", value: title, name: "Title", minChars: 3 },
        { type: "number", value: category, name: "Category" },
        { type: "string", value: description, name: "Description", minChars: 100 },
        { type: "string", value: content, name: "Content", minChars: 200 },
        { type: "image", value: cover, name: "Cover" }
    ]

    const validation = window.validateForm(formInputs)
    if(!validation){
        form.submit()
    }else{
        document.getElementById('errors').style.display = 'block'
        document.getElementById('errors').firstElementChild.innerHTML = ''
        validation.forEach(item => {
            document.getElementById('errors').firstElementChild.innerHTML += `<li>${item}</li>`
        })
    }
})