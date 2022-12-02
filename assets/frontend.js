!function(){var e={875:function(e,t,n){var r;!function(){"use strict";var o=!("undefined"==typeof window||!window.document||!window.document.createElement),a={canUseDOM:o,canUseWorkers:"undefined"!=typeof Worker,canUseEventListeners:o&&!(!window.addEventListener&&!window.attachEvent),canUseViewport:o&&!!window.screen};void 0===(r=function(){return a}.call(t,n,t,e))||(e.exports=r)}()}},t={};function n(r){var o=t[r];if(void 0!==o)return o.exports;var a=t[r]={exports:{}};return e[r](a,a.exports,n),a.exports}n.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return n.d(t,{a:t}),t},n.d=function(e,t){for(var r in t)n.o(t,r)&&!n.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},n.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},function(){"use strict";var e=window.wp.element,t=window.wp.i18n,r=window.React,o=["br","col","colgroup","dl","hr","iframe","img","input","link","menuitem","meta","ol","param","select","table","tbody","tfoot","thead","tr","ul","wbr"],a={"accept-charset":"acceptCharset",acceptcharset:"acceptCharset",accesskey:"accessKey",allowfullscreen:"allowFullScreen",autocapitalize:"autoCapitalize",autocomplete:"autoComplete",autocorrect:"autoCorrect",autofocus:"autoFocus",autoplay:"autoPlay",autosave:"autoSave",cellpadding:"cellPadding",cellspacing:"cellSpacing",charset:"charSet",class:"className",classid:"classID",classname:"className",colspan:"colSpan",contenteditable:"contentEditable",contextmenu:"contextMenu",controlslist:"controlsList",crossorigin:"crossOrigin",dangerouslysetinnerhtml:"dangerouslySetInnerHTML",datetime:"dateTime",defaultchecked:"defaultChecked",defaultvalue:"defaultValue",enctype:"encType",for:"htmlFor",formmethod:"formMethod",formaction:"formAction",formenctype:"formEncType",formnovalidate:"formNoValidate",formtarget:"formTarget",frameborder:"frameBorder",hreflang:"hrefLang",htmlfor:"htmlFor",httpequiv:"httpEquiv","http-equiv":"httpEquiv",icon:"icon",innerhtml:"innerHTML",inputmode:"inputMode",itemid:"itemID",itemprop:"itemProp",itemref:"itemRef",itemscope:"itemScope",itemtype:"itemType",keyparams:"keyParams",keytype:"keyType",marginwidth:"marginWidth",marginheight:"marginHeight",maxlength:"maxLength",mediagroup:"mediaGroup",minlength:"minLength",nomodule:"noModule",novalidate:"noValidate",playsinline:"playsInline",radiogroup:"radioGroup",readonly:"readOnly",referrerpolicy:"referrerPolicy",rowspan:"rowSpan",spellcheck:"spellCheck",srcdoc:"srcDoc",srclang:"srcLang",srcset:"srcSet",tabindex:"tabIndex",typemustmatch:"typeMustMatch",usemap:"useMap",accentheight:"accentHeight","accent-height":"accentHeight",alignmentbaseline:"alignmentBaseline","alignment-baseline":"alignmentBaseline",allowreorder:"allowReorder",arabicform:"arabicForm","arabic-form":"arabicForm",attributename:"attributeName",attributetype:"attributeType",autoreverse:"autoReverse",basefrequency:"baseFrequency",baselineshift:"baselineShift","baseline-shift":"baselineShift",baseprofile:"baseProfile",calcmode:"calcMode",capheight:"capHeight","cap-height":"capHeight",clippath:"clipPath","clip-path":"clipPath",clippathunits:"clipPathUnits",cliprule:"clipRule","clip-rule":"clipRule",colorinterpolation:"colorInterpolation","color-interpolation":"colorInterpolation",colorinterpolationfilters:"colorInterpolationFilters","color-interpolation-filters":"colorInterpolationFilters",colorprofile:"colorProfile","color-profile":"colorProfile",colorrendering:"colorRendering","color-rendering":"colorRendering",contentscripttype:"contentScriptType",contentstyletype:"contentStyleType",diffuseconstant:"diffuseConstant",dominantbaseline:"dominantBaseline","dominant-baseline":"dominantBaseline",edgemode:"edgeMode",enablebackground:"enableBackground","enable-background":"enableBackground",externalresourcesrequired:"externalResourcesRequired",fillopacity:"fillOpacity","fill-opacity":"fillOpacity",fillrule:"fillRule","fill-rule":"fillRule",filterres:"filterRes",filterunits:"filterUnits",floodopacity:"floodOpacity","flood-opacity":"floodOpacity",floodcolor:"floodColor","flood-color":"floodColor",fontfamily:"fontFamily","font-family":"fontFamily",fontsize:"fontSize","font-size":"fontSize",fontsizeadjust:"fontSizeAdjust","font-size-adjust":"fontSizeAdjust",fontstretch:"fontStretch","font-stretch":"fontStretch",fontstyle:"fontStyle","font-style":"fontStyle",fontvariant:"fontVariant","font-variant":"fontVariant",fontweight:"fontWeight","font-weight":"fontWeight",glyphname:"glyphName","glyph-name":"glyphName",glyphorientationhorizontal:"glyphOrientationHorizontal","glyph-orientation-horizontal":"glyphOrientationHorizontal",glyphorientationvertical:"glyphOrientationVertical","glyph-orientation-vertical":"glyphOrientationVertical",glyphref:"glyphRef",gradienttransform:"gradientTransform",gradientunits:"gradientUnits",horizadvx:"horizAdvX","horiz-adv-x":"horizAdvX",horizoriginx:"horizOriginX","horiz-origin-x":"horizOriginX",imagerendering:"imageRendering","image-rendering":"imageRendering",kernelmatrix:"kernelMatrix",kernelunitlength:"kernelUnitLength",keypoints:"keyPoints",keysplines:"keySplines",keytimes:"keyTimes",lengthadjust:"lengthAdjust",letterspacing:"letterSpacing","letter-spacing":"letterSpacing",lightingcolor:"lightingColor","lighting-color":"lightingColor",limitingconeangle:"limitingConeAngle",markerend:"markerEnd","marker-end":"markerEnd",markerheight:"markerHeight",markermid:"markerMid","marker-mid":"markerMid",markerstart:"markerStart","marker-start":"markerStart",markerunits:"markerUnits",markerwidth:"markerWidth",maskcontentunits:"maskContentUnits",maskunits:"maskUnits",numoctaves:"numOctaves",overlineposition:"overlinePosition","overline-position":"overlinePosition",overlinethickness:"overlineThickness","overline-thickness":"overlineThickness",paintorder:"paintOrder","paint-order":"paintOrder","panose-1":"panose1",pathlength:"pathLength",patterncontentunits:"patternContentUnits",patterntransform:"patternTransform",patternunits:"patternUnits",pointerevents:"pointerEvents","pointer-events":"pointerEvents",pointsatx:"pointsAtX",pointsaty:"pointsAtY",pointsatz:"pointsAtZ",preservealpha:"preserveAlpha",preserveaspectratio:"preserveAspectRatio",primitiveunits:"primitiveUnits",refx:"refX",refy:"refY",renderingintent:"renderingIntent","rendering-intent":"renderingIntent",repeatcount:"repeatCount",repeatdur:"repeatDur",requiredextensions:"requiredExtensions",requiredfeatures:"requiredFeatures",shaperendering:"shapeRendering","shape-rendering":"shapeRendering",specularconstant:"specularConstant",specularexponent:"specularExponent",spreadmethod:"spreadMethod",startoffset:"startOffset",stddeviation:"stdDeviation",stitchtiles:"stitchTiles",stopcolor:"stopColor","stop-color":"stopColor",stopopacity:"stopOpacity","stop-opacity":"stopOpacity",strikethroughposition:"strikethroughPosition","strikethrough-position":"strikethroughPosition",strikethroughthickness:"strikethroughThickness","strikethrough-thickness":"strikethroughThickness",strokedasharray:"strokeDasharray","stroke-dasharray":"strokeDasharray",strokedashoffset:"strokeDashoffset","stroke-dashoffset":"strokeDashoffset",strokelinecap:"strokeLinecap","stroke-linecap":"strokeLinecap",strokelinejoin:"strokeLinejoin","stroke-linejoin":"strokeLinejoin",strokemiterlimit:"strokeMiterlimit","stroke-miterlimit":"strokeMiterlimit",strokewidth:"strokeWidth","stroke-width":"strokeWidth",strokeopacity:"strokeOpacity","stroke-opacity":"strokeOpacity",suppresscontenteditablewarning:"suppressContentEditableWarning",suppresshydrationwarning:"suppressHydrationWarning",surfacescale:"surfaceScale",systemlanguage:"systemLanguage",tablevalues:"tableValues",targetx:"targetX",targety:"targetY",textanchor:"textAnchor","text-anchor":"textAnchor",textdecoration:"textDecoration","text-decoration":"textDecoration",textlength:"textLength",textrendering:"textRendering","text-rendering":"textRendering",underlineposition:"underlinePosition","underline-position":"underlinePosition",underlinethickness:"underlineThickness","underline-thickness":"underlineThickness",unicodebidi:"unicodeBidi","unicode-bidi":"unicodeBidi",unicoderange:"unicodeRange","unicode-range":"unicodeRange",unitsperem:"unitsPerEm","units-per-em":"unitsPerEm",unselectable:"unselectable",valphabetic:"vAlphabetic","v-alphabetic":"vAlphabetic",vectoreffect:"vectorEffect","vector-effect":"vectorEffect",vertadvy:"vertAdvY","vert-adv-y":"vertAdvY",vertoriginx:"vertOriginX","vert-origin-x":"vertOriginX",vertoriginy:"vertOriginY","vert-origin-y":"vertOriginY",vhanging:"vHanging","v-hanging":"vHanging",videographic:"vIdeographic","v-ideographic":"vIdeographic",viewbox:"viewBox",viewtarget:"viewTarget",vmathematical:"vMathematical","v-mathematical":"vMathematical",wordspacing:"wordSpacing","word-spacing":"wordSpacing",writingmode:"writingMode","writing-mode":"writingMode",xchannelselector:"xChannelSelector",xheight:"xHeight","x-height":"xHeight",xlinkactuate:"xlinkActuate","xlink:actuate":"xlinkActuate",xlinkarcrole:"xlinkArcrole","xlink:arcrole":"xlinkArcrole",xlinkhref:"xlinkHref","xlink:href":"xlinkHref",xlinkrole:"xlinkRole","xlink:role":"xlinkRole",xlinkshow:"xlinkShow","xlink:show":"xlinkShow",xlinktitle:"xlinkTitle","xlink:title":"xlinkTitle",xlinktype:"xlinkType","xlink:type":"xlinkType",xmlbase:"xmlBase","xml:base":"xmlBase",xmllang:"xmlLang","xml:lang":"xmlLang","xml:space":"xmlSpace",xmlnsxlink:"xmlnsXlink","xmlns:xlink":"xmlnsXlink",xmlspace:"xmlSpace",ychannelselector:"yChannelSelector",zoomandpan:"zoomAndPan",onblur:"onBlur",onchange:"onChange",onclick:"onClick",oncontextmenu:"onContextMenu",ondoubleclick:"onDoubleClick",ondrag:"onDrag",ondragend:"onDragEnd",ondragenter:"onDragEnter",ondragexit:"onDragExit",ondragleave:"onDragLeave",ondragover:"onDragOver",ondragstart:"onDragStart",ondrop:"onDrop",onerror:"onError",onfocus:"onFocus",oninput:"onInput",oninvalid:"onInvalid",onkeydown:"onKeyDown",onkeypress:"onKeyPress",onkeyup:"onKeyUp",onload:"onLoad",onmousedown:"onMouseDown",onmouseenter:"onMouseEnter",onmouseleave:"onMouseLeave",onmousemove:"onMouseMove",onmouseout:"onMouseOut",onmouseover:"onMouseOver",onmouseup:"onMouseUp",onscroll:"onScroll",onsubmit:"onSubmit",ontouchcancel:"onTouchCancel",ontouchend:"onTouchEnd",ontouchmove:"onTouchMove",ontouchstart:"onTouchStart",onwheel:"onWheel"},i=function(){return i=Object.assign||function(e){for(var t,n=1,r=arguments.length;n<r;n++)for(var o in t=arguments[n])Object.prototype.hasOwnProperty.call(t,o)&&(e[o]=t[o]);return e},i.apply(this,arguments)},l=function(e,t){var n="function"==typeof Symbol&&e[Symbol.iterator];if(!n)return e;var r,o,a=n.call(e),i=[];try{for(;(void 0===t||t-- >0)&&!(r=a.next()).done;)i.push(r.value)}catch(e){o={error:e}}finally{try{r&&!r.done&&(n=a.return)&&n.call(a)}finally{if(o)throw o.error}}return i},s=function(e,t){for(var n=0,r=t.length,o=e.length;n<r;n++,o++)e[o]=t[n];return e};function c(e,t){var n={key:t};if(e instanceof Element){var r=e.getAttribute("class");r&&(n.className=r),s([],l(e.attributes)).forEach((function(e){switch(e.name){case"class":break;case"style":n[e.name]=e.value.split(/ ?; ?/).reduce((function(e,t){var n=function(e,t){var n="function"==typeof Symbol&&e[Symbol.iterator];if(!n)return e;var r,o,a=n.call(e),i=[];try{for(;(void 0===t||t-- >0)&&!(r=a.next()).done;)i.push(r.value)}catch(e){o={error:e}}finally{try{r&&!r.done&&(n=a.return)&&n.call(a)}finally{if(o)throw o.error}}return i}(t.split(/ ?: ?/),2),r=n[0],o=n[1];return r&&o&&(e[r.replace(/-(\w)/g,(function(e,t){return t.toUpperCase()}))]=Number.isNaN(Number(o))?o:Number(o)),e}),{});break;case"allowfullscreen":case"allowpaymentrequest":case"async":case"autofocus":case"autoplay":case"checked":case"controls":case"default":case"defer":case"disabled":case"formnovalidate":case"hidden":case"ismap":case"itemscope":case"loop":case"multiple":case"muted":case"nomodule":case"novalidate":case"open":case"readonly":case"required":case"reversed":case"selected":case"typemustmatch":n[a[e.name]||e.name]=!0;break;default:n[a[e.name]||e.name]=e.value}}))}return n}function u(e,t){var n;if(void 0===t&&(t={}),!(e&&e instanceof Node))return null;var a,d=t.actions,p=void 0===d?[]:d,h=t.index,m=void 0===h?0:h,f=t.level,g=void 0===f?0:f,v=t.randomKey,y=e,k=g+"-"+m,E=[];if(v&&0===g&&(k=function(e){void 0===e&&(e=6);for(var t="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ",n="",r=e;r>0;--r)n+=t[Math.round(Math.random()*(t.length-1))];return n}()+"-"+k),Array.isArray(p)&&p.forEach((function(t){t.condition(y,k,g)&&("function"==typeof t.pre&&((y=t.pre(y,k,g))instanceof Node||(y=e)),"function"==typeof t.post&&E.push(t.post(y,k,g)))})),E.length)return E;switch(y.nodeType){case 1:return r.createElement((a=y.nodeName,/[a-z]+[A-Z]+[a-z]+/.test(a)?a:a.toLowerCase()),c(y,k),function(e,t,n){var r=s([],l(e)).map((function(e,r){return u(e,i(i({},n),{index:r,level:t+1}))})).filter(Boolean);return r.length?r:null}(y.childNodes,g,t));case 3:var x=(null===(n=y.nodeValue)||void 0===n?void 0:n.toString())||"";if(/^\s+$/.test(x)&&!/[\u202F\u00A0]/.test(x))return null;if(!y.parentNode)return x;var b=y.parentNode.nodeName.toLowerCase();return-1!==o.indexOf(b)?(/\S/.test(x)&&console.warn("A textNode is not allowed inside '"+b+"'. Your text \""+x+'" will be ignored'),null):x;default:return null}}function d(e,t){return void 0===t&&(t={}),"string"==typeof e?function(e,t){if(void 0===t&&(t={}),!e||"string"!=typeof e)return null;var n=t.nodeOnly,r=void 0!==n&&n,o=t.selector,a=void 0===o?"body > *":o,i=t.type,l=void 0===i?"text/html":i;try{var s=(new DOMParser).parseFromString(e,l).querySelector(a);if(!(s instanceof Node))throw new Error("Error parsing input");return r?s:u(s,t)}catch(e){}return null}(e,t):e instanceof Node?u(e,t):null}var p=n(875),h={FAILED:"failed",LOADED:"loaded",LOADING:"loading",PENDING:"pending",READY:"ready",UNSUPPORTED:"unsupported"};function m(){return p.canUseDOM}var f,g=(f=function(e,t){return f=Object.setPrototypeOf||{__proto__:[]}instanceof Array&&function(e,t){e.__proto__=t}||function(e,t){for(var n in t)Object.prototype.hasOwnProperty.call(t,n)&&(e[n]=t[n])},f(e,t)},function(e,t){if("function"!=typeof t&&null!==t)throw new TypeError("Class extends value "+String(t)+" is not a constructor or null");function __(){this.constructor=e}f(e,t),e.prototype=null===t?Object.create(t):(__.prototype=t.prototype,new __)}),v=function(){return v=Object.assign||function(e){for(var t,n=1,r=arguments.length;n<r;n++)for(var o in t=arguments[n])Object.prototype.hasOwnProperty.call(t,o)&&(e[o]=t[o]);return e},v.apply(this,arguments)},y=function(e,t){var n="function"==typeof Symbol&&e[Symbol.iterator];if(!n)return e;var r,o,a=n.call(e),i=[];try{for(;(void 0===t||t-- >0)&&!(r=a.next()).done;)i.push(r.value)}catch(e){o={error:e}}finally{try{r&&!r.done&&(n=a.return)&&n.call(a)}finally{if(o)throw o.error}}return i},k=Object.create(null),E=function(e){function t(t){var n=e.call(this,t)||this;return n.isActive=!1,n.handleCacheQueue=function(e){"string"!=typeof e?n.handleError(e):n.handleLoad(e)},n.handleLoad=function(e){n.isActive&&n.setState({content:e,status:h.LOADED},n.getElement)},n.handleError=function(e){var t=n.props.onError,r="Browser does not support SVG"===e.message?h.UNSUPPORTED:h.FAILED;n.isActive&&n.setState({status:r},(function(){"function"==typeof t&&t(e)}))},n.request=function(){var e=n.props,t=e.cacheRequests,r=e.fetchOptions,o=e.src;try{return t&&(k[o]={content:"",status:h.LOADING,queue:[]}),fetch(o,r).then((function(e){var t=e.headers.get("content-type"),n=y((t||"").split(/ ?; ?/),1)[0];if(e.status>299)throw new Error("Not found");if(!["image/svg+xml","text/plain"].some((function(e){return n.indexOf(e)>=0})))throw new Error("Content type isn't valid: "+n);return e.text()})).then((function(e){var r=n.props.src;if(o===r&&(n.handleLoad(e),t)){var a=k[o];a&&(a.content=e,a.status=h.LOADED,a.queue=a.queue.filter((function(t){return t(e),!1})))}})).catch((function(e){if(n.handleError(e),t){var r=k[o];r&&(r.queue.forEach((function(t){t(e)})),delete k[o])}}))}catch(e){return n.handleError(new Error(e.message))}},n.state={content:"",element:null,hasCache:!!t.cacheRequests&&!!k[t.src],status:h.PENDING},n.hash=t.uniqueHash||function(e){for(var t,n="abcdefghijklmnopqrstuvwxyz",r=""+n+n.toUpperCase()+"1234567890",o="",a=0;a<8;a++)o+=(t=r)[Math.floor(Math.random()*t.length)];return o}(),n}return g(t,e),t.prototype.componentDidMount=function(){if(this.isActive=!0,m()){var e=this.state.status,t=this.props.src;try{if(e===h.PENDING){if(!function(){if(!document)return!1;var e=document.createElement("div");return e.innerHTML="<svg />",!!e.firstChild&&"http://www.w3.org/2000/svg"===e.firstChild.namespaceURI}()||"undefined"==typeof window||null===window)throw new Error("Browser does not support SVG");if(!t)throw new Error("Missing src");this.load()}}catch(e){this.handleError(e)}}},t.prototype.componentDidUpdate=function(e,t){if(m()){var n=this.state,r=n.hasCache,o=n.status,a=this.props,i=a.onLoad,l=a.src;if(t.status!==h.READY&&o===h.READY&&i&&i(l,r),e.src!==l){if(!l)return void this.handleError(new Error("Missing src"));this.load()}}},t.prototype.componentWillUnmount=function(){this.isActive=!1},t.prototype.processSVG=function(){var e=this.state.content,t=this.props.preProcessor;return t?t(e):e},t.prototype.updateSVGAttributes=function(e){var t=this,n=this.props,r=n.baseURL,o=void 0===r?"":r,a=n.uniquifyIDs,i=["id","href","xlink:href","xlink:role","xlink:arcrole"],l=["href","xlink:href"];return a?(function(e,t){for(var n=0,r=t.length,o=e.length;n<r;n++,o++)e[o]=t[n];return e}([],y(e.children)).map((function(e){if(e.attributes&&e.attributes.length){var n=Object.values(e.attributes).map((function(e){var n=e,r=e.value.match(/url\((.*?)\)/);return r&&r[1]&&(n.value=e.value.replace(r[0],"url("+o+r[1]+"__"+t.hash+")")),n}));i.forEach((function(e){var r,o,a=n.find((function(t){return t.name===e}));!a||(r=e,o=a.value,l.indexOf(r)>=0&&o&&o.indexOf("#")<0)||(a.value=a.value+"__"+t.hash)}))}return e.children.length?t.updateSVGAttributes(e):e})),e):e},t.prototype.getNode=function(){var e=this.props,t=e.description,n=e.title;try{var r=d(this.processSVG(),{nodeOnly:!0});if(!(r&&r instanceof SVGSVGElement))throw new Error("Could not convert the src to a DOM Node");var o=this.updateSVGAttributes(r);if(t){var a=o.querySelector("desc");a&&a.parentNode&&a.parentNode.removeChild(a);var i=document.createElement("desc");i.innerHTML=t,o.prepend(i)}if(n){var l=o.querySelector("title");l&&l.parentNode&&l.parentNode.removeChild(l);var s=document.createElement("title");s.innerHTML=n,o.prepend(s)}return o}catch(e){return this.handleError(e)}},t.prototype.getElement=function(){try{var e=d(this.getNode());if(!e||!r.isValidElement(e))throw new Error("Could not convert the src to a React element");this.setState({element:e,status:h.READY})}catch(e){this.handleError(new Error(e.message))}},t.prototype.load=function(){var e=this;this.isActive&&this.setState({content:"",element:null,status:h.LOADING},(function(){var t=e.props,n=t.cacheRequests,r=t.src,o=n&&k[r];if(o)o.status===h.LOADING?o.queue.push(e.handleCacheQueue):o.status===h.LOADED&&e.handleLoad(o.content);else{var a,i=r.match(/data:image\/svg[^,]*?(;base64)?,(.*)/);i?a=i[1]?atob(i[2]):decodeURIComponent(i[2]):r.indexOf("<svg")>=0&&(a=r),a?e.handleLoad(a):e.request()}}))},t.prototype.render=function(){var e=this.state,t=e.element,n=e.status,o=this.props,a=o.children,i=void 0===a?null:a,l=o.innerRef,s=o.loader,c=void 0===s?null:s,u=function(e){for(var t=[],n=1;n<arguments.length;n++)t[n-1]=arguments[n];var r={};for(var o in e)({}).hasOwnProperty.call(e,o)&&(t.includes(o)||(r[o]=e[o]));return r}(this.props,"baseURL","cacheRequests","children","description","fetchOptions","innerRef","loader","onError","onLoad","preProcessor","src","title","uniqueHash","uniquifyIDs");return m()?t?r.cloneElement(t,v({ref:l},u)):[h.UNSUPPORTED,h.FAILED].indexOf(n)>-1?i:c:c},t.defaultProps={cacheRequests:!0,uniquifyIDs:!1},t}(r.PureComponent),x=E,b=t=>{const{options:n,label:o,value:a,setValue:i,placeholder:l}=t,[s,c]=(0,r.useState)(!1),[u,d]=(0,r.useState)(""),[p,h]=(0,r.useState)(n);return(0,e.createElement)("div",{className:"input combobox "+(s?"combobox--open":"")},(0,e.createElement)("input",{type:"hidden",value:a}),(0,e.createElement)("div",{tabIndex:0,onFocus:()=>{c(!0)},onBlur:()=>{c(!1)}},(0,e.createElement)("label",null,o),(0,e.createElement)("input",{type:"text",value:u,onClick:()=>{d("")},onChange:e=>{(e=>{h((t=>n.filter((t=>t.title.toLowerCase().includes(e.toLowerCase()))))),d(e)})(e.target.value)}}),(0,e.createElement)("ul",{className:"options"},p.map((t=>(0,e.createElement)("li",{onClick:()=>{(e=>{i(e.name),d(e.title),c(!1)})(t)}},t.thumbnail&&(0,e.createElement)("img",{src:t.thumbnail}),!t.thumbnail&&(0,e.createElement)("div",{className:"combobox__dummy"}),(0,e.createElement)("div",null,t.title,(0,e.createElement)("br",null),(0,e.createElement)("span",null,t.description))))))))},w=()=>{const{countries:n,projects:o}=window.fundingScriptData,[a,i]=(0,r.useState)(""),[l,s]=(0,r.useState)(""),[c,u]=(0,r.useState)(25),[d,p]=(0,r.useState)({result:!1});return(0,e.createElement)("div",{className:"grid grid--columns-2 grid--gap-12"},(0,e.createElement)("div",null,(0,e.createElement)("form",{className:"form grid grid--columns-1 grid--gap-8"},(0,e.createElement)("div",{className:"select"},(0,e.createElement)("label",null,(0,t.__)("Your Country","funding")),(0,e.createElement)("select",{onChange:e=>{s(e.target.value)}},(0,e.createElement)("option",null,(0,t.__)("Select Country","funding")),n.map((t=>(0,e.createElement)("option",{value:t.name},t.title))))),(0,e.createElement)(b,{options:o,label:(0,t.__)("Project","funding"),value:a,setValue:i}),(0,e.createElement)("div",{className:"input"},(0,e.createElement)("label",null,(0,t.__)("Amount","funding")),(0,e.createElement)("input",{value:c,onChange:e=>{u(e.target.value)},type:"number"})),(0,e.createElement)("div",{className:"button-group button-group--right"},(0,e.createElement)("a",{onClick:()=>{""!=a&&""!=l&&fetch(`/wp-admin/admin-ajax.php?action=funding_payment_info&project=${a}&country=${l}`).then((e=>e.json())).then((e=>{e&&p(e)}))},class:"button button--primary"+(c&&l&&a?"":" button--disabled"),href:"#/"},(0,t.__)("Show Data","funding"))),(0,e.createElement)("div",{className:"mt-auto"}))),(0,e.createElement)("div",{className:"result"},d.result&&(0,e.createElement)("div",null,(0,e.createElement)("div",{className:"card bg-white card--center card--no-image card--shadow"},(0,e.createElement)("h3",{className:"card__title"},(0,t.__)("Scan to pay","funding")),(0,e.createElement)(x,{className:"w-full",src:`/wp-admin/admin-ajax.php?action=funding_qr_code&project=${a}&country=${l}`})),(0,e.createElement)("table",{class:"mt-12 table--dotted"},(0,e.createElement)("tr",null,(0,e.createElement)("td",null,(0,t.__)("IBAN","funding")),(0,e.createElement)("td",null,d.iban)),(0,e.createElement)("tr",null,(0,e.createElement)("td",null,(0,t.__)("Purpose","funding")),(0,e.createElement)("td",null,d.purpose)),(0,e.createElement)("tr",null,(0,e.createElement)("td",null,(0,t.__)("BIC","funding")),(0,e.createElement)("td",null,d.bic)),(0,e.createElement)("tr",null,(0,e.createElement)("td",null,(0,t.__)("Beneficiary","funding")),(0,e.createElement)("td",null,d.beneficiary)),(0,e.createElement)("tr",null,(0,e.createElement)("td",null,(0,t.__)("Bank","funding")),(0,e.createElement)("td",null,d.bank)),(0,e.createElement)("tr",null,(0,e.createElement)("td",null,(0,t.__)("Amount","funding")),(0,e.createElement)("td",null,c))))))},S=window.ReactDOM,O=n.n(S);const D=document.getElementById("dmmTransferApp");document.addEventListener("click",(e=>{e.target.classList.contains("open--booking")&&openBookingModal()})),window.addEventListener("message",(function(e){if("https://em.altruja.de"!==e.origin)return;const t=JSON.parse(e.data);if("resize"!==t.name)return;let n=document.getElementById("altruja");n&&(console.log(n),n.height=t.height+50)}),!1),D&&document.addEventListener("DOMContentLoaded",(()=>{O().render((0,e.createElement)(w,null),D)}))}()}();