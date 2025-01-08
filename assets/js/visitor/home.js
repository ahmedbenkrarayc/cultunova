const articlesContainer = document.getElementById('articlesContainer')
const keyword = document.getElementById('keyword')
const category = document.getElementById('category')
let data = []
let filteredData = []
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
    filteredData.forEach((item) => {
        articlesContainer.innerHTML += `
        <div class="col-sm-6 col-lg-4">
            <div class="card card-sm">
                <a href="#" class="d-block"><img src="${item.cover}"></a>
                <div class="card-body">
                <div class="d-flex align-items-center">
                    <div>
                    <div>${item.title}</div>
                    <div class="text-secondary">2025-01-01</div>
                    </div>
                </div>
                </div>
            </div>
        </div>
        `
    })
}

const filter = () => {
    
}

(async () => {
    await getArticles()
    filteredData = [...data]
    display()
})()