const articlesContainer = document.getElementById('articlesContainer')
const keyword = document.getElementById('keyword')
const category = document.getElementById('category')
const form = document.getElementById('form')
const clear = document.getElementById('reset')
const showmore = document.getElementById('showmore')

let data = []
let filteredData = []
let perPage = 10
let currentPage = 1

const getArticles = async () => {
    try{
        const response = await fetch('/requests/articles.php')
        const res = await response.json()
        if(res.success){
            data = res.data
        }
    }catch(err){
        console.error(err)
    }
}

const display = () => {
    articlesContainer.innerHTML = ''
    filteredData.slice(0, (currentPage * perPage)).forEach((item) => {
        articlesContainer.innerHTML += `
        <div class="col-sm-6 col-lg-4">
            <div class="card card-sm">
                <a href="./../author/article.php?id=${item.id}" class="d-block"><img src="${item.cover}"></a>
                <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                    <div>${item.title}</div>
                    <div class="text-secondary">${item.updatedAt.split(' ')[0]}</div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        `
    })
}

const filter = () => {
    filteredData = [...data]
    perPage = 10
    currentPage = 1

    if(category.value != 'all')
        filteredData = filteredData.filter((item) => item.category_id == category.value)
    else
        filteredData = [...data]
    if(keyword.value.trim() != '')
        filteredData = filteredData.filter((item) => item.title.toLowerCase().includes(keyword.value.toLowerCase()) || item.content.toLowerCase().includes(keyword.value.toLowerCase()) || item.description.toLowerCase().includes(keyword.value.toLowerCase()) || (item.author_fname.toLowerCase()+' '+item.author_lname.toLowerCase()).includes(keyword.value.toLowerCase()))
}

const reset = () => {
    keyword.value = ''
    category.value = 'all'
}

(async () => {
    await getArticles()
    filteredData = [...data]
    display()

    form.addEventListener('submit', (e) => {
        e.preventDefault()
        filter()
        display()
    })

    clear.addEventListener('click', reset)
    showmore.addEventListener('click', () => {
        currentPage += 1;
        display()
    })
})()