document.addEventListener("DOMContentLoaded",()=>{


// ==========================
// SIDEBAR
// ==========================

const menuBtn = document.querySelector("#menu-btn");

const sidebar = document.querySelector("#sidebar");

const content = document.querySelector("#content");



menuBtn.addEventListener("click",()=>{


    sidebar.classList.toggle("active");

    content.classList.toggle("active");


});





// ==========================
// MODAL CRIAR
// ==========================


const btnCriar = document.querySelector("#btn-criar");

const modalCriar = document.querySelector("#modalCriar");

const fecharCriar = document.querySelector("#fecharCriar");



btnCriar.addEventListener("click",()=>{


    modalCriar.classList.add("active");


});



fecharCriar.addEventListener("click",()=>{


    modalCriar.classList.remove("active");


});




// ==========================
// MODAL EDITAR
// ==========================


const botoesEditar = document.querySelectorAll(".btn-editar");


const modalEditar = document.querySelector("#modalEditar");

const fecharEditar = document.querySelector("#fecharEditar");


const formEditar = document.querySelector("#formEditar");



const nome = document.querySelector("#editarNome");

const descricao = document.querySelector("#editarDescricao");

const prazo = document.querySelector("#editarPrazo");

const status = document.querySelector("#editarStatus");





botoesEditar.forEach(botao=>{


    botao.addEventListener("click",()=>{


        let id = botao.dataset.id;


        nome.value = botao.dataset.nome;

        descricao.value = botao.dataset.desc;

        prazo.value = botao.dataset.prazo;

        status.value = botao.dataset.status;



        formEditar.action="/tarefas/"+id;



        modalEditar.classList.add("active");



    });



});





fecharEditar.addEventListener("click",()=>{


    modalEditar.classList.remove("active");


});



});