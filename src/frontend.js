import Transfer from './apps/transfer/Transfer.jsx';

import React from "react";
import ReactDOM from "react-dom";


const root = document.getElementById('dmmTransferApp');

document.addEventListener('click', (event) => {
  if(!event.target.classList.contains("open--booking")) return;
  openBookingModal();
})

window.addEventListener('message', function (event) {
	if(event.origin !== 'https://em.altruja.de') return;
	
	const data = JSON.parse(event.data);
	if(data.name !== 'resize') return;

	let altruja = document.getElementById('altruja');
	if(!altruja) return;
	console.log(altruja)
	altruja.height = data.height + 50;

}, false);


if(root) {
document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <Transfer />,
     root
    );
  })
}