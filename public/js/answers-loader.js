import e from"./answer-edit.js";import s from"./comment-edit.js";import n from"./comments-loader.js";import t from"./interactions.js";let answersContainer=document.getElementById("answers-container"),answersSort=document.getElementById("answers-sort");async function fetchAnswers(){let e=window.location.href.split("/").pop(),s=await fetch("/api/answers?question_id="+e+"&sort="+answersSort.value),n=await s.json();return n.answers}async function loadAnswers(){let r=await fetchAnswers(),a=r.map(e=>e+'<hr class="m-0">').join("");answersContainer.innerHTML=a,s(),e(),n(),t()}export default loadAnswers;