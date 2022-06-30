(()=>{"use strict";var t,e,a=new Uint8Array(16);function n(){if(!e&&!(e="undefined"!=typeof crypto&&crypto.getRandomValues&&crypto.getRandomValues.bind(crypto)||"undefined"!=typeof msCrypto&&"function"==typeof msCrypto.getRandomValues&&msCrypto.getRandomValues.bind(msCrypto)))throw new Error("crypto.getRandomValues() not supported. See https://github.com/uuidjs/uuid#getrandomvalues-not-supported");return e(a)}const r=/^(?:[0-9a-f]{8}-[0-9a-f]{4}-[1-5][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}|00000000-0000-0000-0000-000000000000)$/i,o=function(t){return"string"==typeof t&&r.test(t)};for(var s=[],i=0;i<256;++i)s.push((i+256).toString(16).substr(1));const l=function(t,e,a){var r=(t=t||{}).random||(t.rng||n)();if(r[6]=15&r[6]|64,r[8]=63&r[8]|128,e){a=a||0;for(var i=0;i<16;++i)e[a+i]=r[i];return e}return function(t){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:0,a=(s[t[e+0]]+s[t[e+1]]+s[t[e+2]]+s[t[e+3]]+"-"+s[t[e+4]]+s[t[e+5]]+"-"+s[t[e+6]]+s[t[e+7]]+"-"+s[t[e+8]]+s[t[e+9]]+"-"+s[t[e+10]]+s[t[e+11]]+s[t[e+12]]+s[t[e+13]]+s[t[e+14]]+s[t[e+15]]).toLowerCase();if(!o(a))throw TypeError("Stringified UUID is invalid");return a}(r)};function c(t){return(c="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(t){return typeof t}:function(t){return t&&"function"==typeof Symbol&&t.constructor===Symbol&&t!==Symbol.prototype?"symbol":typeof t})(t)}function u(t,e){for(var a=0;a<e.length;a++){var n=e[a];n.enumerable=n.enumerable||!1,n.configurable=!0,"value"in n&&(n.writable=!0),Object.defineProperty(t,n.key,n)}}function d(t,e){return(d=Object.setPrototypeOf||function(t,e){return t.__proto__=e,t})(t,e)}function m(t,e){return!e||"object"!==c(e)&&"function"!=typeof e?function(t){if(void 0===t)throw new ReferenceError("this hasn't been initialised - super() hasn't been called");return t}(t):e}function g(t){return(g=Object.setPrototypeOf?Object.getPrototypeOf:function(t){return t.__proto__||Object.getPrototypeOf(t)})(t)}var h=wp.i18n.__,f=wp.element.render;function p(t){var e="yasr-ranking-element-"+l(),a=document.getElementById(t.tableId).dataset.rankingSize;return React.createElement("div",{id:e,ref:function(){return function(t,e){var a,n=arguments.length>3&&void 0!==arguments[3]?arguments[3]:.1,r=!(arguments.length>4&&void 0!==arguments[4])||arguments[4],o=arguments.length>5&&void 0!==arguments[5]&&arguments[5],s=arguments.length>6&&void 0!==arguments[6]&&arguments[6];a=arguments.length>2&&void 0!==arguments[2]&&arguments[2]||document.getElementById(e),t=parseInt(t),raterJs({starSize:t,showToolTip:!1,element:a,step:n,readOnly:r,rating:o,rateCallback:s})}(a,e,!1,.1,!0,t.rating)}})}function y(t){if(void 0!==t.post.number_of_votes)return React.createElement("span",{className:"yasr-most-rated-text"},"[",h("Total:","yet-another-stars-rating")," ",t.post.number_of_votes,"  ",h("Average:","yet-another-stars-rating")," ",t.post.rating,"]");var e=t.text;return React.createElement("span",{className:"yasr-highest-rated-text"},e," ",t.post.rating)}function v(e){return React.createElement("td",{className:e.colClass},React.createElement("a",{href:e.post.link},function(e){if("string"!=typeof e||-1===e.indexOf("&"))return e;void 0===t&&(t=document.implementation&&document.implementation.createHTMLDocument?document.implementation.createHTMLDocument("").createElement("textarea"):document.createElement("textarea")),t.innerHTML=e;var a=t.textContent;return t.innerHTML="",a}(e.post.title)))}function b(t){var e="after",a=h("Rating:","yet-another-stars-rating"),n=new URLSearchParams(t.rankingParams);return null!==n.get("text_position")&&(e=n.get("text_position")),null!==n.get("custom_txt")&&(a=n.get("custom_txt")),"before"===e?React.createElement("td",{className:t.colClass},React.createElement(y,{post:t.post,tableId:t.tableId,text:a}),React.createElement(p,{rating:t.post.rating,tableId:t.tableId})):React.createElement("td",{className:t.colClass},React.createElement(p,{rating:t.post.rating,tableId:t.tableId}),React.createElement(y,{post:t.post,tableId:t.tableId,text:a}))}function R(t){var e="",a="";return"author_ranking"===t.source?(e="yasr-top-10-overall-left",a="yasr-top-10-overall-right"):"visitor_votes"===t.source&&(e="yasr-top-10-most-highest-left",a="yasr-top-10-most-highest-right"),React.createElement("tr",{className:t.trClass},React.createElement(v,{colClass:e,post:t.post}),React.createElement(b,{colClass:a,post:t.post,tableId:t.tableId,rankingParams:t.rankingParams}))}function E(t){return React.createElement("tbody",{id:t.tBodyId,style:{display:t.show}},t.data.map((function(e,a){var n="yasr-rankings-td-colored";return"author_ranking"===t.source&&(n="yasr-rankings-td-white"),a%2==0&&(n="yasr-rankings-td-white","author_ranking"===t.source&&(n="yasr-rankings-td-colored")),React.createElement(R,{key:e.post_id,source:t.source,tableId:t.tableId,rankingParams:t.rankingParams,post:e,trClass:n})})))}var _=function(t){!function(t,e){if("function"!=typeof e&&null!==e)throw new TypeError("Super expression must either be null or a function");t.prototype=Object.create(e&&e.prototype,{constructor:{value:t,writable:!0,configurable:!0}}),e&&d(t,e)}(s,React.Component);var e,a,n,r,o=(n=s,r=function(){if("undefined"==typeof Reflect||!Reflect.construct)return!1;if(Reflect.construct.sham)return!1;if("function"==typeof Proxy)return!0;try{return Boolean.prototype.valueOf.call(Reflect.construct(Boolean,[],(function(){}))),!0}catch(t){return!1}}(),function(){var t,e=g(n);if(r){var a=g(this).constructor;t=Reflect.construct(e,arguments,a)}else t=e.apply(this,arguments);return m(this,t)});function s(t){var e;return function(t,e){if(!(t instanceof e))throw new TypeError("Cannot call a class as a function")}(this,s),(e=o.call(this,t)).state={error:null,isLoaded:!1,data:[],tableId:t.tableId,source:t.source,rankingParams:t.params,nonce:t.nonce},e}return e=s,(a=[{key:"componentDidMount",value:function(){var t=this,e=JSON.parse(document.getElementById(this.state.tableId).dataset.rankingData),a={};if("yes"!==yasrCommonData.ajaxEnabled)console.info(h("Ajax Disabled, getting data from source","yet-another-stars-rating")),this.setState({isLoaded:!0,data:e});else if(this.state.source){var n=this.returnRestUrl();Promise.all(n.map((function(t){return fetch(t).then((function(t){return!0===t.ok?t.json():(console.info(h("Ajax Call Failed. Getting data from source")),"KO")})).then((function(t){"KO"===t?a=e:"overall_rating"===t.source||"author_multi"===t.source?a="overall_rating"===t.source?t.data_overall:t.data_mv:a[t.show]=t.data_vv})).catch((function(t){a=e,console.info(h(t))}))}))).then((function(e){t.setState({isLoaded:!0,data:a})})).catch((function(e){console.info(h(e)),t.setState({isLoaded:!0,data:a})}))}else this.setState({error:h("Invalid Data Source","yet-another-stars-rating")})}},{key:"returnRestUrl",value:function(){var t,e=""!==this.state.rankingParams?this.state.rankingParams:"",a=this.state.source,n="&nonce_rankings="+this.state.nonce,r="";if(""!==e&&!1!==e){var o=new URLSearchParams(e);null!==o.get("order_by")&&(r+="order_by="+o.get("order_by")),null!==o.get("limit")&&(r+="&limit="+o.get("limit")),null!==o.get("start_date")&&"0"!==o.get("start_date")&&(r+="&start_date="+o.get("start_date")),null!==o.get("end_date")&&"0"!==o.get("end_date")&&(r+="&end_date="+o.get("end_date")),null!==o.get("ctg")?r+="&ctg="+o.get("ctg"):null!==o.get("cpt")&&(r+="&cpt="+o.get("cpt")),""!==r&&(r="&"+(r=r.replace(/\s+/g,""))),"visitor_multi"!==a&&"author_multi"!==a||null!==o.get("setid")&&(r+="&setid="+o.get("setid"))}else r="";if("author_ranking"===a||"author_multi"===a)t=[yasrCommonData.ajaxurl+"?action=yasr_load_rankings&source="+a+r+n];else{var s="",i="";if(""!==e){var l=new URLSearchParams(e);null!==l.get("required_votes[most]")&&(s="&required_votes="+l.get("required_votes[most]")),null!==l.get("required_votes[highest]")&&(i="&required_votes="+l.get("required_votes[highest]"))}t=[yasrCommonData.ajaxurl+"?action=yasr_load_rankings&show=most&source="+a+r+s+n,yasrCommonData.ajaxurl+"?action=yasr_load_rankings&show=highest&source="+a+r+i+n]}return t}},{key:"rankingTableHead",value:function(t,e){var a=this.state.tableId,n="link-most-rated-posts-"+a,r="link-highest-rated-posts-"+a;if("author_ranking"!==t){var o=React.createElement("span",null,React.createElement("span",{id:n},h("Most Rated","yet-another-stars-rating"))," | ",React.createElement("a",{href:"#",id:r,onClick:this.switchTBody.bind(this)},h("Highest Rated","yet-another-stars-rating")));return"highest"===e&&(o=React.createElement("span",null,React.createElement("span",{id:r},h("Highest Rated","yet-another-stars-rating"))," | ",React.createElement("a",{href:"#",id:n,onClick:this.switchTBody.bind(this)},h("Most Rated","yet-another-stars-rating")))),React.createElement("thead",null,React.createElement("tr",{className:"yasr-rankings-td-colored yasr-rankings-heading"},React.createElement("th",null,"Post"),React.createElement("th",null,h("Order By","yet-another-stars-rating-pro"),":  ",o)))}return React.createElement(React.Fragment,null)}},{key:"switchTBody",value:function(t){t.preventDefault();var e=t.target.id,a=this.state.tableId,n="link-most-rated-posts-"+a,r="link-highest-rated-posts-"+a,o="most-rated-posts-"+a,s="highest-rated-posts-"+a,i=document.getElementById(e),l=document.createElement("span");l.innerHTML=i.innerHTML,l.id=i.id,i.parentNode.replaceChild(l,i),e===n&&(document.getElementById(s).style.display="none",document.getElementById(o).style.display="",l=document.getElementById(r),i.innerHTML=l.innerHTML,i.id=l.id,l.parentNode.replaceChild(i,l)),e===r&&(document.getElementById(o).style.display="none",document.getElementById(s).style.display="",l=document.getElementById(n),i.innerHTML=l.innerHTML,i.id=l.id,l.parentNode.replaceChild(i,l))}},{key:"rankingTableBody",value:function(){var t=this.state,e=t.data,a=t.source,n=t.rankingParams;if("overall_rating"===a||"author_multi"===a)return React.createElement(E,{data:e,tableId:this.state.tableId,tBodyId:"overall_"+this.state.tableId,rankingParams:n,show:"table-row-group",source:a});var r=e.most,o=e.highest,s="table-row-group",i="none",l="most",c=s,u=i,d=new URLSearchParams(n);return null!==d.get("view")&&(l=d.get("view")),"highest"===l&&(c=i,u=s),React.createElement(React.Fragment,null,this.rankingTableHead(a,l),React.createElement(E,{data:r,tableId:this.state.tableId,tBodyId:"most-rated-posts-"+this.state.tableId,rankingParams:n,show:c,source:a}),React.createElement(E,{data:o,tableId:this.state.tableId,tBodyId:"highest-rated-posts-"+this.state.tableId,rankingParams:n,show:u,source:a}))}},{key:"render",value:function(){var t=this.state,e=t.error,a=t.isLoaded;return e?React.createElement("tbody",null,React.createElement("tr",null,React.createElement("td",null,console.log(e),"Error"))):!1===a?React.createElement("tbody",null,React.createElement("tr",null,React.createElement("td",null,h("Loading Charts","yet-another-stars-rating")))):React.createElement(React.Fragment,null,this.rankingTableBody())}}])&&u(e.prototype,a),s}();!function(){var t=document.getElementsByClassName("yasr-stars-rankings");if(t.length>0)for(var e=0;e<t.length;e++){var a=t.item(e).id,n=JSON.parse(t.item(e).dataset.rankingSource),r=JSON.parse(t.item(e).dataset.rankingParams),o=JSON.parse(t.item(e).dataset.rankingNonce),s=document.getElementById(a);f(React.createElement(_,{source:n,tableId:a,params:r,nonce:o}),s)}}()})();