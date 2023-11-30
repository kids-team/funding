!function(){"use strict";var e,t={869:function(e,t,n){var r={};n.r(r),n.d(r,{category:function(){return h},metadata:function(){return m},name:function(){return w},settings:function(){return E}});var i={};n.r(i),n.d(i,{category:function(){return N},metadata:function(){return _},name:function(){return x},settings:function(){return S}});var a={};n.r(a),n.d(a,{category:function(){return P},metadata:function(){return M},name:function(){return D},settings:function(){return V}});var o=window.wp.blocks,l=window.React,c=window.wp.i18n,s=window.wp.blockEditor,u=window.wp.components,d=window.wp.serverSideRender,f=n.n(d),g=(0,l.createElement)(u.SVG,{xmlns:"http://www.w3.org/2000/svg","enable-background":"new 0 0 24 24",height:"24px",viewBox:"0 0 24 24",width:"24px",fill:"#000000"},(0,l.createElement)("g",null,(0,l.createElement)("rect",{fill:"none",height:"24",width:"24"})),(0,l.createElement)("g",null,(0,l.createElement)("g",null,(0,l.createElement)("rect",{height:"11",width:"4",x:"1",y:"11"}),(0,l.createElement)("path",{d:"M16,3.25C16.65,2.49,17.66,2,18.7,2C20.55,2,22,3.45,22,5.3c0,2.27-2.91,4.9-6,7.7c-3.09-2.81-6-5.44-6-7.7 C10,3.45,11.45,2,13.3,2C14.34,2,15.35,2.49,16,3.25z"}),(0,l.createElement)("path",{d:"M20,17h-7l-2.09-0.73l0.33-0.94L13,16h2.82c0.65,0,1.18-0.53,1.18-1.18v0c0-0.49-0.31-0.93-0.77-1.11L8.97,11H7v9.02 L14,22l8.01-3v0C22,17.9,21.11,17,20,17z"})))),m=JSON.parse('{"name":"funding/mollie","api_version":2,"category":"widgets","attributes":{"preview":{"type":"boolean","default":false}}}');const{withColors:p}=wp.blockEditor,{__:__}=wp.i18n,{name:w,category:h,attributes:v,api_version:b}=m,E={title:__("Donate with Mollie","funding"),description:__("Shows a donation form with direct online payment","funding"),icon:g,attributes:v,apiVersion:b,keywords:["funding",__("funding","funding"),__("donation","funding")],edit:({attributes:e,setAttributes:t})=>{const{preview:n}=e,r=(0,s.useBlockProps)({className:"funding-mollie"});return t({preview:!1}),(0,l.createElement)("div",{...r},(0,l.createElement)("div",{className:"components-placeholder is-large"},(0,l.createElement)("div",{className:"components-placeholder__label"},(0,l.createElement)("span",{className:"block-editor-block-icon has-colors"},(0,l.createElement)(u.Icon,{className:"ctx-row-icon",icon:g})),(0,c.__)("Mollie Donations","funding")),(0,l.createElement)(f(),{block:"funding/mollie",attributes:{preview:!0}})))},save:()=>null};var y=(0,l.createElement)(u.SVG,{xmlns:"http://www.w3.org/2000/svg","enable-background":"new 0 0 24 24",height:"24px",viewBox:"0 0 24 24",width:"24px",fill:"#000000"},(0,l.createElement)("g",null,(0,l.createElement)("rect",{fill:"none",height:"24",width:"24"})),(0,l.createElement)("g",null,(0,l.createElement)("g",null,(0,l.createElement)("rect",{height:"7",width:"3",x:"4",y:"10"}),(0,l.createElement)("rect",{height:"7",width:"3",x:"10.5",y:"10"}),(0,l.createElement)("rect",{height:"3",width:"20",x:"2",y:"19"}),(0,l.createElement)("rect",{height:"7",width:"3",x:"17",y:"10"}),(0,l.createElement)("polygon",{points:"12,1 2,6 2,8 22,8 22,6"})))),_=JSON.parse('{"name":"funding/transfer","api_version":2,"category":"widgets","attributes":{"preview":{"type":"boolean","default":false}}}');const{__:k}=wp.i18n,{name:x,category:N,attributes:O,api_version:C}=_,S={title:k("Donate with Bank Transfer","funding"),description:k("Shows a donation form with a QR-Code for manual donation","funding"),icon:y,attributes:O,apiVersion:C,keywords:["funding",k("funding","funding"),k("donation","funding")],edit:({attributes:e,setAttributes:t})=>{const n=(0,s.useBlockProps)({className:"funding-transfer"});return(0,l.createElement)("div",{...n},(0,l.createElement)("div",{className:"components-placeholder is-large"},(0,l.createElement)("div",{className:"components-placeholder__label"},(0,l.createElement)("span",{className:"block-editor-block-icon has-colors"},(0,l.createElement)(u.Icon,{className:"ctx-row-icon",icon:y})),(0,c.__)("Transfer Donations","funding")),(0,l.createElement)(f(),{block:"funding/transfer",attributes:{preview:!0}})))},save:()=>null};var j=(0,l.createElement)(u.SVG,{xmlns:"http://www.w3.org/2000/svg","enable-background":"new 0 0 24 24",height:"24px",viewBox:"0 0 24 24",width:"24px",fill:"#000000"},(0,l.createElement)("g",null,(0,l.createElement)("rect",{fill:"none",height:"24",width:"24",y:"0"})),(0,l.createElement)("g",null,(0,l.createElement)("path",{d:"M19.48,12.35c-1.57-4.08-7.16-4.3-5.81-10.23c0.1-0.44-0.37-0.78-0.75-0.55C9.29,3.71,6.68,8,8.87,13.62 c0.18,0.46-0.36,0.89-0.75,0.59c-1.81-1.37-2-3.34-1.84-4.75c0.06-0.52-0.62-0.77-0.91-0.34C4.69,10.16,4,11.84,4,14.37 c0.38,5.6,5.11,7.32,6.81,7.54c2.43,0.31,5.06-0.14,6.95-1.87C19.84,18.11,20.6,15.03,19.48,12.35z M10.2,17.38 c1.44-0.35,2.18-1.39,2.38-2.31c0.33-1.43-0.96-2.83-0.09-5.09c0.33,1.87,3.27,3.04,3.27,5.08C15.84,17.59,13.1,19.76,10.2,17.38z"}))),M=JSON.parse('{"name":"funding/altruja","api_version":2,"category":"widgets","attributes":{"preview":{"type":"boolean","default":false},"eid":{"type":"string","default":""}}}');const{__:B}=wp.i18n,{name:D,category:P,attributes:A,api_version:T}=M,V={title:B("Donate with Altruja","funding"),description:B("Shows an iFrame with the Altruja donation form","funding"),icon:j,attributes:A,apiVersion:2,keywords:["funding",B("funding","funding"),B("donation","funding")],edit:({attributes:e,setAttributes:t})=>{const{eid:n}=e,r=(0,s.useBlockProps)({className:"funding-transfer"});return(0,l.createElement)("div",{...r},(0,l.createElement)("div",{className:"components-placeholder is-large"},(0,l.createElement)("div",{className:"components-placeholder__label"},(0,c.__)("Altruja Donations","funding")),(0,l.createElement)(u.TextControl,{label:(0,c.__)("Donator URL part"),value:n,onChange:e=>{t({eid:e})}})))},save:()=>null};[r,i,a].forEach((e=>{if(!e)return;const{name:t,category:n,settings:r}=e;(0,o.registerBlockType)(t,{category:n,...r})}))}},n={};function r(e){var i=n[e];if(void 0!==i)return i.exports;var a=n[e]={exports:{}};return t[e](a,a.exports,r),a.exports}r.m=t,e=[],r.O=function(t,n,i,a){if(!n){var o=1/0;for(u=0;u<e.length;u++){n=e[u][0],i=e[u][1],a=e[u][2];for(var l=!0,c=0;c<n.length;c++)(!1&a||o>=a)&&Object.keys(r.O).every((function(e){return r.O[e](n[c])}))?n.splice(c--,1):(l=!1,a<o&&(o=a));if(l){e.splice(u--,1);var s=i();void 0!==s&&(t=s)}}return t}a=a||0;for(var u=e.length;u>0&&e[u-1][2]>a;u--)e[u]=e[u-1];e[u]=[n,i,a]},r.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return r.d(t,{a:t}),t},r.d=function(e,t){for(var n in t)r.o(t,n)&&!r.o(e,n)&&Object.defineProperty(e,n,{enumerable:!0,get:t[n]})},r.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},r.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},function(){var e={154:0,780:0};r.O.j=function(t){return 0===e[t]};var t=function(t,n){var i,a,o=n[0],l=n[1],c=n[2],s=0;if(o.some((function(t){return 0!==e[t]}))){for(i in l)r.o(l,i)&&(r.m[i]=l[i]);if(c)var u=c(r)}for(t&&t(n);s<o.length;s++)a=o[s],r.o(e,a)&&e[a]&&e[a][0](),e[a]=0;return r.O(u)},n=self.webpackChunkfunding=self.webpackChunkfunding||[];n.forEach(t.bind(null,0)),n.push=t.bind(null,n.push.bind(n))}();var i=r.O(void 0,[780],(function(){return r(869)}));i=r.O(i)}();