parcelRequire=function(e,r,t,n){var i,o="function"==typeof parcelRequire&&parcelRequire,u="function"==typeof require&&require;function f(t,n){if(!r[t]){if(!e[t]){var i="function"==typeof parcelRequire&&parcelRequire;if(!n&&i)return i(t,!0);if(o)return o(t,!0);if(u&&"string"==typeof t)return u(t);var c=new Error("Cannot find module '"+t+"'");throw c.code="MODULE_NOT_FOUND",c}p.resolve=function(r){return e[t][1][r]||r},p.cache={};var l=r[t]=new f.Module(t);e[t][0].call(l.exports,p,l,l.exports,this)}return r[t].exports;function p(e){return f(p.resolve(e))}}f.isParcelRequire=!0,f.Module=function(e){this.id=e,this.bundle=f,this.exports={}},f.modules=e,f.cache=r,f.parent=o,f.register=function(r,t){e[r]=[function(e,r){r.exports=t},{}]};for(var c=0;c<t.length;c++)try{f(t[c])}catch(e){i||(i=e)}if(t.length){var l=f(t[t.length-1]);"object"==typeof exports&&"undefined"!=typeof module?module.exports=l:"function"==typeof define&&define.amd?define(function(){return l}):n&&(this[n]=l)}if(parcelRequire=f,i)throw i;return f}({"kZS2":[function(require,module,exports) {
"use strict";Object.defineProperty(exports,"__esModule",{value:!0}),exports.default=void 0;var e=wp.components.SelectControl,o=wp.i18n.__;function t(t){var n=t.value,a=t.options,l=t.onChange;return console.log({value:n,options:a,onChange:l}),React.createElement(e,{label:o("Forum"),labelPosition:"top",value:n,options:a,onChange:l})}var n=t;exports.default=n;
},{}],"aE2q":[function(require,module,exports) {
"use strict";var e=t(require("./components/forumPicker"));function t(e){return e&&e.__esModule?e:{default:e}}var r=wp.blocks.registerBlockType,n=wp.components,o=n.Placeholder,s=n.TextControl,c=wp.blockEditor.BlockIcon,i=wp.i18n.__;r("bbpress/forum-index",{title:i("Forums List"),icon:"buddicons-bbpress-logo",category:"common",attributes:{},edit:function(){return React.createElement(o,{icon:React.createElement(c,{icon:"buddicons-forums"}),label:i("bbPress Forum Index"),instructions:i("This will display your entire forum index.")})},save:function(){return null}}),r("bbpress/forum-form",{title:i("New Forum Form"),icon:"buddicons-bbpress-logo",category:"common",attributes:{},edit:function(){return React.createElement(o,{icon:React.createElement(c,{icon:"buddicons-forums"}),label:i("bbPress New Forum Form"),instructions:i("Display the ‘New Forum’ form.")})},save:function(){return null}}),r("bbpress/forum",{title:i("Single Forum"),icon:"buddicons-bbpress-logo",category:"common",attributes:{id:{default:0}},edit:function(t){return React.createElement(o,{icon:React.createElement(c,{icon:"buddicons-forums"}),label:i("bbPress Single Forum"),instructions:i("Display a single forum’s topics.")},React.createElement(e.default,{value:t.attributes.id,options:bbpBlocks.data.forums,onChange:function(e){return t.setAttributes({id:e})}}))},save:function(){return null}}),r("bbpress/search",{title:i("Search Results"),icon:"buddicons-bbpress-logo",category:"common",attributes:{search:{default:""}},edit:function(e){return React.createElement(o,{icon:React.createElement(c,{icon:"search"}),label:i("Search Results"),instructions:i("Display the search results for a given query.")},React.createElement(s,{label:i("Search Term"),value:e.attributes.search,onChange:function(t){return e.setAttributes({search:t})}}))},save:function(){return null}}),r("bbpress/search-form",{title:i("Search Form"),icon:"buddicons-bbpress-logo",category:"common",attributes:{},edit:function(){return React.createElement(o,{icon:React.createElement(c,{icon:"search"}),label:i("Search Form"),instructions:i("Display the search form template.")})},save:function(){return null}}),r("bbpress/login",{title:i("Login"),icon:"buddicons-bbpress-logo",category:"common",attributes:{},edit:function(){return React.createElement(o,{icon:React.createElement(c,{icon:"admin-users"}),label:i("Login Screen"),instructions:i("Display the login screen.")})},save:function(){return null}}),r("bbpress/register",{title:i("Register"),icon:"buddicons-bbpress-logo",category:"common",attributes:{},edit:function(){return React.createElement(o,{icon:React.createElement(c,{icon:"admin-users"}),label:i("Register Screen"),instructions:i("Display the register screen.")})},save:function(){return null}}),r("bbpress/lost-pass",{title:i("Lost Password Form"),icon:"buddicons-bbpress-logo",category:"common",attributes:{},edit:function(){return React.createElement(o,{icon:React.createElement(c,{icon:"admin-users"}),label:i("Lost Password Form"),instructions:i("Display the lost password screen.")})},save:function(){return null}}),r("bbpress/stats",{title:i("Forum Statistics"),icon:"buddicons-bbpress-logo",category:"common",attributes:{},edit:function(){return React.createElement(o,{icon:React.createElement(c,{icon:"chart-line"}),label:i("bbPress Forum Statistics"),instructions:i("Display the forum statistics.")})},save:function(){return null}});
},{"./components/forumPicker":"kZS2"}]},{},["aE2q"], null)
//# sourceMappingURL=blocks.js.map