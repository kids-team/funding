import Transfer from './apps/transfer/Transfer.jsx';

import React from "react";
import ReactDOM from "react-dom";


const root = document.getElementById('dmmTransferApp');

document.addEventListener('click', (event) => {
  if(!event.target.classList.contains("open--booking")) return;
  openBookingModal();
})

if(root) {
document.addEventListener('DOMContentLoaded', () => {
  ReactDOM.render(
    <Transfer />,
     root
    );
  })
}