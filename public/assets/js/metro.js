
 var globalIP = document.location.host;
 var context = '/gps';

var MetronicApp = angular.module("MetronicApp", ['ui.select']); 

!function(){"use strict";function e(e){return angular.isUndefined(e)||null===e}var t={TAB:9,ENTER:13,ESC:27,SPACE:32,LEFT:37,UP:38,RIGHT:39,DOWN:40,SHIFT:16,CTRL:17,ALT:18,PAGE_UP:33,PAGE_DOWN:34,HOME:36,END:35,BACKSPACE:8,DELETE:46,COMMAND:91,MAP:{91:"COMMAND",8:"BACKSPACE",9:"TAB",13:"ENTER",16:"SHIFT",17:"CTRL",18:"ALT",19:"PAUSEBREAK",20:"CAPSLOCK",27:"ESC",32:"SPACE",33:"PAGE_UP",34:"PAGE_DOWN",35:"END",36:"HOME",37:"LEFT",38:"UP",39:"RIGHT",40:"DOWN",43:"+",44:"PRINTSCREEN",45:"INSERT",46:"DELETE",48:"0",49:"1",50:"2",51:"3",52:"4",53:"5",54:"6",55:"7",56:"8",57:"9",59:";",61:"=",65:"A",66:"B",67:"C",68:"D",69:"E",70:"F",71:"G",72:"H",73:"I",74:"J",75:"K",76:"L",77:"M",78:"N",79:"O",80:"P",81:"Q",82:"R",83:"S",84:"T",85:"U",86:"V",87:"W",88:"X",89:"Y",90:"Z",96:"0",97:"1",98:"2",99:"3",100:"4",101:"5",102:"6",103:"7",104:"8",105:"9",106:"*",107:"+",109:"-",110:".",111:"/",112:"F1",113:"F2",114:"F3",115:"F4",116:"F5",117:"F6",118:"F7",119:"F8",120:"F9",121:"F10",122:"F11",123:"F12",144:"NUMLOCK",145:"SCROLLLOCK",186:";",187:"=",188:",",189:"-",190:".",191:"/",192:"`",219:"[",220:"\\",221:"]",222:"'"},isControl:function(e){var s=e.which;switch(s){case t.COMMAND:case t.SHIFT:case t.CTRL:case t.ALT:return!0}return!!(e.metaKey||e.ctrlKey||e.altKey)},isFunctionKey:function(e){return e=e.which?e.which:e,e>=112&&e<=123},isVerticalMovement:function(e){return~[t.UP,t.DOWN].indexOf(e)},isHorizontalMovement:function(e){return~[t.LEFT,t.RIGHT,t.BACKSPACE,t.DELETE].indexOf(e)},toSeparator:function(e){var s={ENTER:"\n",TAB:"\t",SPACE:" "}[e];return s?s:t[e]?void 0:e}};void 0===angular.element.prototype.querySelectorAll&&(angular.element.prototype.querySelectorAll=function(e){return angular.element(this[0].querySelectorAll(e))}),void 0===angular.element.prototype.closest&&(angular.element.prototype.closest=function(e){for(var t=this[0],s=t.matches||t.webkitMatchesSelector||t.mozMatchesSelector||t.msMatchesSelector;t;){if(s.bind(t)(e))return t;t=t.parentElement}return!1});var s=0,i=angular.module("ui.select",[]).constant("uiSelectConfig",{theme:"bootstrap",searchEnabled:!0,sortable:!1,placeholder:"",refreshDelay:1e3,closeOnSelect:!0,skipFocusser:!1,dropdownPosition:"auto",removeSelected:!0,resetSearchInput:!0,generateId:function(){return s++},appendToBody:!1,spinnerEnabled:!1,spinnerClass:"glyphicon glyphicon-refresh ui-select-spin",backspaceReset:!0}).service("uiSelectMinErr",function(){var e=angular.$$minErr("ui.select");return function(){var t=e.apply(this,arguments),s=t.message.replace(new RegExp("\nhttp://errors.angularjs.org/.*"),"");return new Error(s)}}).directive("uisTranscludeAppend",function(){return{link:function(e,t,s,i,c){c(e,function(e){t.append(e)})}}}).filter("highlight",function(){function e(e){return(""+e).replace(/([.?*+^$[\]\\(){}|-])/g,"\\$1")}return function(t,s){return s&&t?(""+t).replace(new RegExp(e(s),"gi"),'<span class="ui-select-highlight">$&</span>'):t}}).factory("uisOffset",["$document","$window",function(e,t){return function(s){var i=s[0].getBoundingClientRect();return{width:i.width||s.prop("offsetWidth"),height:i.height||s.prop("offsetHeight"),top:i.top+(t.pageYOffset||e[0].documentElement.scrollTop),left:i.left+(t.pageXOffset||e[0].documentElement.scrollLeft)}}}]);i.directive("uiSelectChoices",["uiSelectConfig","uisRepeatParser","uiSelectMinErr","$compile","$window",function(e,t,s,i,c){return{restrict:"EA",require:"^uiSelect",replace:!0,transclude:!0,templateUrl:function(t){t.addClass("ui-select-choices");var s=t.parent().attr("theme")||e.theme;return s+"/choices.tpl.html"},compile:function(i,n){if(!n.repeat)throw s("repeat","Expected 'repeat' expression.");var l=n.groupBy,a=n.groupFilter;if(l){var r=i.querySelectorAll(".ui-select-choices-group");if(1!==r.length)throw s("rows","Expected 1 .ui-select-choices-group but got '{0}'.",r.length);r.attr("ng-repeat",t.getGroupNgRepeatExpression())}var o=t.parse(n.repeat),u=i.querySelectorAll(".ui-select-choices-row");if(1!==u.length)throw s("rows","Expected 1 .ui-select-choices-row but got '{0}'.",u.length);u.attr("ng-repeat",o.repeatExpression(l)).attr("ng-if","$select.open");var d=i.querySelectorAll(".ui-select-choices-row-inner");if(1!==d.length)throw s("rows","Expected 1 .ui-select-choices-row-inner but got '{0}'.",d.length);d.attr("uis-transclude-append","");var p=c.document.addEventListener?u:d;return p.attr("ng-click","$select.select("+o.itemName+",$select.skipFocusser,$event)"),function(t,s,c,n){n.parseRepeatAttr(c.repeat,l,a),n.disableChoiceExpression=c.uiDisableChoice,n.onHighlightCallback=c.onHighlight,n.minimumInputLength=parseInt(c.minimumInputLength)||0,n.dropdownPosition=c.position?c.position.toLowerCase():e.dropdownPosition,t.$watch("$select.search",function(e){e&&!n.open&&n.multiple&&n.activate(!1,!0),n.activeIndex=n.tagging.isActivated?-1:0,!c.minimumInputLength||n.search.length>=c.minimumInputLength?n.refresh(c.refresh):n.items=[]}),c.$observe("refreshDelay",function(){var s=t.$eval(c.refreshDelay);n.refreshDelay=void 0!==s?s:e.refreshDelay}),t.$watch("$select.open",function(e){e?(i.attr("role","listbox"),n.refresh(c.refresh)):s.removeAttr("role")})}}}}]),i.controller("uiSelectCtrl",["$scope","$element","$timeout","$filter","$$uisDebounce","uisRepeatParser","uiSelectMinErr","uiSelectConfig","$parse","$injector","$window",function(s,i,c,n,l,a,r,o,u,d,p){function h(e,t,s){if(e.findIndex)return e.findIndex(t,s);for(var i,c=Object(e),n=c.length>>>0,l=0;l<n;l++)if(i=c[l],t.call(s,i,l,c))return l;return-1}function g(){y.resetSearchInput&&(y.search=x,y.selected&&y.items.length&&!y.multiple&&(y.activeIndex=h(y.items,function(e){return angular.equals(this,e)},y.selected)))}function f(e,t){var s,i,c=[];for(s=0;s<t.length;s++)for(i=0;i<e.length;i++)e[i].name==[t[s]]&&c.push(e[i]);return c}function v(e,t){var s=I.indexOf(e);t&&s===-1&&I.push(e),!t&&s>-1&&I.splice(s,1)}function m(e){return I.indexOf(e)>-1}function $(e){function t(e,t){var s=i.indexOf(e);t&&s===-1&&i.push(e),!t&&s>-1&&i.splice(s,1)}function s(e){return i.indexOf(e)>-1}if(e){var i=[];y.isLocked=function(e,i){var c=!1,n=y.selected[i];return n&&(e?(c=!!e.$eval(y.lockChoiceExpression),t(n,c)):c=s(n)),c}}}function b(e){var s=!0;switch(e){case t.DOWN:if(!y.open&&y.multiple)y.activate(!1,!0);else if(y.activeIndex<y.items.length-1)for(var i=++y.activeIndex;m(y.items[i])&&i<y.items.length;)y.activeIndex=++i;break;case t.UP:var c=0===y.search.length&&y.tagging.isActivated?-1:0;if(!y.open&&y.multiple)y.activate(!1,!0);else if(y.activeIndex>c)for(var n=--y.activeIndex;m(y.items[n])&&n>c;)y.activeIndex=--n;break;case t.TAB:y.multiple&&!y.open||y.select(y.items[y.activeIndex],!0);break;case t.ENTER:y.open&&(y.tagging.isActivated||y.activeIndex>=0)?y.select(y.items[y.activeIndex],y.skipFocusser):y.activate(!1,!0);break;case t.ESC:y.close();break;default:s=!1}return s}function w(){var e=i.querySelectorAll(".ui-select-choices-content"),t=e.querySelectorAll(".ui-select-choices-row");if(t.length<1)throw r("choices","Expected multiple .ui-select-choices-row but got '{0}'.",t.length);if(!(y.activeIndex<0)){var s=t[y.activeIndex],c=s.offsetTop+s.clientHeight-e[0].scrollTop,n=e[0].offsetHeight;c>n?e[0].scrollTop+=c-n:c<s.clientHeight&&(y.isGrouped&&0===y.activeIndex?e[0].scrollTop=0:e[0].scrollTop-=s.clientHeight-c)}}var y=this,x="";if(y.placeholder=o.placeholder,y.searchEnabled=o.searchEnabled,y.sortable=o.sortable,y.refreshDelay=o.refreshDelay,y.paste=o.paste,y.resetSearchInput=o.resetSearchInput,y.refreshing=!1,y.spinnerEnabled=o.spinnerEnabled,y.spinnerClass=o.spinnerClass,y.removeSelected=o.removeSelected,y.closeOnSelect=!0,y.skipFocusser=!1,y.search=x,y.activeIndex=0,y.items=[],y.open=!1,y.focus=!1,y.disabled=!1,y.selected=void 0,y.dropdownPosition="auto",y.focusser=void 0,y.multiple=void 0,y.disableChoiceExpression=void 0,y.tagging={isActivated:!1,fct:void 0},y.taggingTokens={isActivated:!1,tokens:void 0},y.lockChoiceExpression=void 0,y.clickTriggeredSelect=!1,y.$filter=n,y.$element=i,y.$animate=function(){try{return d.get("$animate")}catch(e){return null}}(),y.searchInput=i.querySelectorAll("input.ui-select-search"),1!==y.searchInput.length)throw r("searchInput","Expected 1 input.ui-select-search but got '{0}'.",y.searchInput.length);y.isEmpty=function(){return e(y.selected)||""===y.selected||y.multiple&&0===y.selected.length},y.activate=function(e,t){if(y.disabled||y.open)y.open&&!y.searchEnabled&&y.close();else{t||g(),s.$broadcast("uis:activate"),y.open=!0,y.activeIndex=y.activeIndex>=y.items.length?0:y.activeIndex,y.activeIndex===-1&&y.taggingLabel!==!1&&(y.activeIndex=0);var n=i.querySelectorAll(".ui-select-choices-content"),l=i.querySelectorAll(".ui-select-search");if(y.$animate&&y.$animate.on&&y.$animate.enabled(n[0])){var a=function(t,s){"start"===s&&0===y.items.length?(y.$animate.off("removeClass",l[0],a),c(function(){y.focusSearchInput(e)})):"close"===s&&(y.$animate.off("enter",n[0],a),c(function(){y.focusSearchInput(e)}))};y.items.length>0?y.$animate.on("enter",n[0],a):y.$animate.on("removeClass",l[0],a)}else c(function(){y.focusSearchInput(e),!y.tagging.isActivated&&y.items.length>1&&w()})}},y.focusSearchInput=function(e){y.search=e||y.search,y.searchInput[0].focus()},y.findGroupByName=function(e){return y.groups&&y.groups.filter(function(t){return t.name===e})[0]},y.parseRepeatAttr=function(e,t,i){function c(e){var c=s.$eval(t);if(y.groups=[],angular.forEach(e,function(e){var t=angular.isFunction(c)?c(e):e[c],s=y.findGroupByName(t);s?s.items.push(e):y.groups.push({name:t,items:[e]})}),i){var n=s.$eval(i);angular.isFunction(n)?y.groups=n(y.groups):angular.isArray(n)&&(y.groups=f(y.groups,n))}y.items=[],y.groups.forEach(function(e){y.items=y.items.concat(e.items)})}function n(e){y.items=e||[]}y.setItemsFn=t?c:n,y.parserResult=a.parse(e),y.isGrouped=!!t,y.itemProperty=y.parserResult.itemName;var l=y.parserResult.source,o=function(){var e=l(s);s.$uisSource=Object.keys(e).map(function(t){var s={};return s[y.parserResult.keyName]=t,s.value=e[t],s})};y.parserResult.keyName&&(o(),y.parserResult.source=u("$uisSource"+y.parserResult.filters),s.$watch(l,function(e,t){e!==t&&o()},!0)),y.refreshItems=function(e){e=e||y.parserResult.source(s);var t=y.selected;if(y.isEmpty()||angular.isArray(t)&&!t.length||!y.multiple||!y.removeSelected)y.setItemsFn(e);else if(void 0!==e&&null!==e){var i=e.filter(function(e){return angular.isArray(t)?t.every(function(t){return!angular.equals(e,t)}):!angular.equals(e,t)});y.setItemsFn(i)}"auto"!==y.dropdownPosition&&"up"!==y.dropdownPosition||s.calculateDropdownPos(),s.$broadcast("uis:refresh")},s.$watchCollection(y.parserResult.source,function(e){if(void 0===e||null===e)y.items=[];else{if(!angular.isArray(e))throw r("items","Expected an array but got '{0}'.",e);y.refreshItems(e),angular.isDefined(y.ngModel.$modelValue)&&(y.ngModel.$modelValue=null)}})};var E;y.refresh=function(e){void 0!==e&&(E&&c.cancel(E),E=c(function(){if(s.$select.search.length>=s.$select.minimumInputLength){var t=s.$eval(e);t&&angular.isFunction(t.then)&&!y.refreshing&&(y.refreshing=!0,t["finally"](function(){y.refreshing=!1}))}},y.refreshDelay))},y.isActive=function(e){if(!y.open)return!1;var t=y.items.indexOf(e[y.itemProperty]),s=t==y.activeIndex;return!(!s||t<0)&&(s&&!angular.isUndefined(y.onHighlightCallback)&&e.$eval(y.onHighlightCallback),s)};var S=function(e){return y.selected&&angular.isArray(y.selected)&&y.selected.filter(function(t){return angular.equals(t,e)}).length>0},I=[];y.isDisabled=function(e){if(y.open){var t=e[y.itemProperty],s=y.items.indexOf(t),i=!1;if(s>=0&&(angular.isDefined(y.disableChoiceExpression)||y.multiple)){if(t.isTag)return!1;y.multiple&&(i=S(t)),!i&&angular.isDefined(y.disableChoiceExpression)&&(i=!!e.$eval(y.disableChoiceExpression)),v(t,i)}return i}},y.select=function(t,i,c){if(e(t)||!m(t)){if(!y.items&&!y.search&&!y.tagging.isActivated)return;if(!t||!m(t)){if(y.clickTriggeredSelect=!1,c&&("click"===c.type||"touchend"===c.type)&&t&&(y.clickTriggeredSelect=!0),y.tagging.isActivated&&y.clickTriggeredSelect===!1){if(y.taggingLabel===!1)if(y.activeIndex<0){if(void 0===t&&(t=void 0!==y.tagging.fct?y.tagging.fct(y.search):y.search),!t||angular.equals(y.items[0],t))return}else t=y.items[y.activeIndex];else if(0===y.activeIndex){if(void 0===t)return;if(void 0!==y.tagging.fct&&"string"==typeof t){if(t=y.tagging.fct(t),!t)return}else"string"==typeof t&&(t=t.replace(y.taggingLabel,"").trim())}if(S(t))return void y.close(i)}g(),s.$broadcast("uis:select",t),y.closeOnSelect&&y.close(i)}}},y.close=function(e){y.open&&(y.ngModel&&y.ngModel.$setTouched&&y.ngModel.$setTouched(),y.open=!1,g(),s.$broadcast("uis:close",e))},y.setFocus=function(){y.focus||y.focusInput[0].focus()},y.clear=function(e){y.select(null),e.stopPropagation(),c(function(){y.focusser[0].focus()},0,!1)},y.toggle=function(e){y.open?(y.close(),e.preventDefault(),e.stopPropagation()):y.activate()},y.isLocked=function(){return!1},s.$watch(function(){return angular.isDefined(y.lockChoiceExpression)&&""!==y.lockChoiceExpression},$);var C=null,k=!1;y.sizeSearchInput=function(){var e=y.searchInput[0],t=y.$element[0],i=function(){return t.clientWidth*!!e.offsetParent},n=function(t){if(0===t)return!1;var s=t-e.offsetLeft;return s<50&&(s=t),y.searchInput.css("width",s+"px"),!0};y.searchInput.css("width","10px"),c(function(){null!==C||n(i())||(C=s.$watch(function(){k||(k=!0,s.$$postDigest(function(){k=!1,n(i())&&(C(),C=null)}))},angular.noop))})},y.searchInput.on("keydown",function(e){var i=e.which;~[t.ENTER,t.ESC].indexOf(i)&&(e.preventDefault(),e.stopPropagation()),s.$apply(function(){var s=!1;if((y.items.length>0||y.tagging.isActivated)&&(b(i)||y.searchEnabled||(e.preventDefault(),e.stopPropagation()),y.taggingTokens.isActivated)){for(var n=0;n<y.taggingTokens.tokens.length;n++)y.taggingTokens.tokens[n]===t.MAP[e.keyCode]&&y.search.length>0&&(s=!0);s&&c(function(){y.searchInput.triggerHandler("tagged");var s=y.search.replace(t.MAP[e.keyCode],"").trim();y.tagging.fct&&(s=y.tagging.fct(s)),s&&y.select(s,!0)})}}),t.isVerticalMovement(i)&&y.items.length>0&&w(),i!==t.ENTER&&i!==t.ESC||(e.preventDefault(),e.stopPropagation())}),y.searchInput.on("paste",function(e){var s;if(s=window.clipboardData&&window.clipboardData.getData?window.clipboardData.getData("Text"):(e.originalEvent||e).clipboardData.getData("text/plain"),s=y.search+s,s&&s.length>0)if(y.taggingTokens.isActivated){for(var i=[],c=0;c<y.taggingTokens.tokens.length;c++){var n=t.toSeparator(y.taggingTokens.tokens[c])||y.taggingTokens.tokens[c];if(s.indexOf(n)>-1){i=s.split(n);break}}0===i.length&&(i=[s]);var l=y.search;angular.forEach(i,function(e){var t=y.tagging.fct?y.tagging.fct(e):e;t&&y.select(t,!0)}),y.search=l||x,e.preventDefault(),e.stopPropagation()}else y.paste&&(y.paste(s),y.search=x,e.preventDefault(),e.stopPropagation())}),y.searchInput.on("tagged",function(){c(function(){g()})});var A=l(function(){y.sizeSearchInput()},50);angular.element(p).bind("resize",A),s.$on("$destroy",function(){y.searchInput.off("keyup keydown tagged blur paste"),angular.element(p).off("resize",A)}),s.$watch("$select.activeIndex",function(e){e&&i.find("input").attr("aria-activedescendant","ui-select-choices-row-"+y.generatedId+"-"+e)}),s.$watch("$select.open",function(e){e||i.find("input").removeAttr("aria-activedescendant")})}]),i.directive("uiSelect",["$document","uiSelectConfig","uiSelectMinErr","uisOffset","$compile","$parse","$timeout",function(e,t,s,i,c,n,l){return{restrict:"EA",templateUrl:function(e,s){var i=s.theme||t.theme;return i+(angular.isDefined(s.multiple)?"/select-multiple.tpl.html":"/select.tpl.html")},replace:!0,transclude:!0,require:["uiSelect","^ngModel"],scope:!0,controller:"uiSelectCtrl",controllerAs:"$select",compile:function(c,a){var r=/{(.*)}\s*{(.*)}/.exec(a.ngClass);if(r){var o="{"+r[1]+", "+r[2]+"}";a.ngClass=o,c.attr("ng-class",o)}return angular.isDefined(a.multiple)?c.append("<ui-select-multiple/>").removeAttr("multiple"):c.append("<ui-select-single/>"),a.inputId&&(c.querySelectorAll("input.ui-select-search")[0].id=a.inputId),function(c,a,r,o,u){function d(e){if(g.open){var t=!1;if(t=window.jQuery?window.jQuery.contains(a[0],e.target):a[0].contains(e.target),!t&&!g.clickTriggeredSelect){var s;if(g.skipFocusser)s=!0;else{var i=["input","button","textarea","select"],n=angular.element(e.target).controller("uiSelect");s=n&&n!==g,s||(s=~i.indexOf(e.target.tagName.toLowerCase()))}g.close(s),c.$digest()}g.clickTriggeredSelect=!1}}function p(){var t=i(a);m=angular.element('<div class="ui-select-placeholder"></div>'),m[0].style.width=t.width+"px",m[0].style.height=t.height+"px",a.after(m),$=a[0].style.width,e.find("body").append(a),a[0].style.position="absolute",a[0].style.left=t.left+"px",a[0].style.top=t.top+"px",a[0].style.width=t.width+"px"}function h(){null!==m&&(m.replaceWith(a),m=null,a[0].style.position="",a[0].style.left="",a[0].style.top="",a[0].style.width=$,g.setFocus())}var g=o[0],f=o[1];g.generatedId=t.generateId(),g.baseTitle=r.title||"Select box",g.focusserTitle=g.baseTitle+" focus",g.focusserId="focusser-"+g.generatedId,g.closeOnSelect=function(){return angular.isDefined(r.closeOnSelect)?n(r.closeOnSelect)():t.closeOnSelect}(),c.$watch("skipFocusser",function(){var e=c.$eval(r.skipFocusser);g.skipFocusser=void 0!==e?e:t.skipFocusser}),g.onSelectCallback=n(r.onSelect),g.onRemoveCallback=n(r.onRemove),g.ngModel=f,g.choiceGrouped=function(e){return g.isGrouped&&e&&e.name},r.tabindex&&r.$observe("tabindex",function(e){g.focusInput.attr("tabindex",e),a.removeAttr("tabindex")}),c.$watch(function(){return c.$eval(r.searchEnabled)},function(e){g.searchEnabled=void 0!==e?e:t.searchEnabled}),c.$watch("sortable",function(){var e=c.$eval(r.sortable);g.sortable=void 0!==e?e:t.sortable}),r.$observe("backspaceReset",function(){var e=c.$eval(r.backspaceReset);g.backspaceReset=void 0===e||e}),r.$observe("limit",function(){g.limit=angular.isDefined(r.limit)?parseInt(r.limit,10):void 0}),c.$watch("removeSelected",function(){var e=c.$eval(r.removeSelected);g.removeSelected=void 0!==e?e:t.removeSelected}),r.$observe("disabled",function(){g.disabled=void 0!==r.disabled&&r.disabled}),r.$observe("resetSearchInput",function(){var e=c.$eval(r.resetSearchInput);g.resetSearchInput=void 0===e||e}),r.$observe("paste",function(){g.paste=c.$eval(r.paste)}),r.$observe("tagging",function(){if(void 0!==r.tagging){var e=c.$eval(r.tagging);g.tagging={isActivated:!0,fct:e!==!0?e:void 0}}else g.tagging={isActivated:!1,fct:void 0}}),r.$observe("taggingLabel",function(){void 0!==r.tagging&&("false"===r.taggingLabel?g.taggingLabel=!1:g.taggingLabel=void 0!==r.taggingLabel?r.taggingLabel:"(new)")}),r.$observe("taggingTokens",function(){if(void 0!==r.tagging){var e=void 0!==r.taggingTokens?r.taggingTokens.split("|"):[",","ENTER"];g.taggingTokens={isActivated:!0,tokens:e}}}),r.$observe("spinnerEnabled",function(){var e=c.$eval(r.spinnerEnabled);g.spinnerEnabled=void 0!==e?e:t.spinnerEnabled}),r.$observe("spinnerClass",function(){var e=r.spinnerClass;g.spinnerClass=void 0!==e?r.spinnerClass:t.spinnerClass}),angular.isDefined(r.autofocus)&&l(function(){g.setFocus()}),angular.isDefined(r.focusOn)&&c.$on(r.focusOn,function(){l(function(){g.setFocus()})}),e.on("click",d),c.$on("$destroy",function(){e.off("click",d)}),u(c,function(e){var t=angular.element("<div>").append(e),i=t.querySelectorAll(".ui-select-match");if(i.removeAttr("ui-select-match"),i.removeAttr("data-ui-select-match"),1!==i.length)throw s("transcluded","Expected 1 .ui-select-match but got '{0}'.",i.length);a.querySelectorAll(".ui-select-match").replaceWith(i);var c=t.querySelectorAll(".ui-select-choices");if(c.removeAttr("ui-select-choices"),c.removeAttr("data-ui-select-choices"),1!==c.length)throw s("transcluded","Expected 1 .ui-select-choices but got '{0}'.",c.length);a.querySelectorAll(".ui-select-choices").replaceWith(c);var n=t.querySelectorAll(".ui-select-no-choice");n.removeAttr("ui-select-no-choice"),n.removeAttr("data-ui-select-no-choice"),1==n.length&&a.querySelectorAll(".ui-select-no-choice").replaceWith(n)});var v=c.$eval(r.appendToBody);(void 0!==v?v:t.appendToBody)&&(c.$watch("$select.open",function(e){e?p():h()}),c.$on("$destroy",function(){h()}));var m=null,$="",b=null,w="direction-up";c.$watch("$select.open",function(){"auto"!==g.dropdownPosition&&"up"!==g.dropdownPosition||c.calculateDropdownPos()});var y=function(e,t){e=e||i(a),t=t||i(b),b[0].style.position="absolute",b[0].style.top=t.height*-1+"px",a.addClass(w)},x=function(e,t){a.removeClass(w),e=e||i(a),t=t||i(b),b[0].style.position="",b[0].style.top=""},E=function(){l(function(){if("up"===g.dropdownPosition)y();else{a.removeClass(w);var t=i(a),s=i(b),c=e[0].documentElement.scrollTop||e[0].body.scrollTop;t.top+t.height+s.height>c+e[0].documentElement.clientHeight?y(t,s):x(t,s)}b[0].style.opacity=1})},S=!1;c.calculateDropdownPos=function(){if(g.open){if(b=angular.element(a).querySelectorAll(".ui-select-dropdown"),0===b.length)return;if(""!==g.search||S||(b[0].style.opacity=0,S=!0),!i(b).height&&g.$animate&&g.$animate.on&&g.$animate.enabled(b)){var e=!0;g.$animate.on("enter",b,function(t,s){"close"===s&&e&&(E(),e=!1)})}else E()}else{if(null===b||0===b.length)return;b[0].style.opacity=0,b[0].style.position="",b[0].style.top="",a.removeClass(w)}}}}}}]),i.directive("uiSelectMatch",["uiSelectConfig",function(e){function t(e,t){return e[0].hasAttribute(t)?e.attr(t):e[0].hasAttribute("data-"+t)?e.attr("data-"+t):e[0].hasAttribute("x-"+t)?e.attr("x-"+t):void 0}return{restrict:"EA",require:"^uiSelect",replace:!0,transclude:!0,templateUrl:function(s){s.addClass("ui-select-match");var i=s.parent(),c=t(i,"theme")||e.theme,n=angular.isDefined(t(i,"multiple"));return c+(n?"/match-multiple.tpl.html":"/match.tpl.html")},link:function(t,s,i,c){function n(e){c.allowClear=!!angular.isDefined(e)&&(""===e||"true"===e.toLowerCase())}c.lockChoiceExpression=i.uiLockChoice,i.$observe("placeholder",function(t){c.placeholder=void 0!==t?t:e.placeholder}),i.$observe("allowClear",n),n(i.allowClear),c.multiple&&c.sizeSearchInput()}}}]),i.directive("uiSelectMultiple",["uiSelectMinErr","$timeout",function(s,i){return{restrict:"EA",require:["^uiSelect","^ngModel"],controller:["$scope","$timeout",function(e,t){var s,i=this,c=e.$select;angular.isUndefined(c.selected)&&(c.selected=[]),e.$evalAsync(function(){s=e.ngModel}),i.activeMatchIndex=-1,i.updateModel=function(){s.$setViewValue(Date.now()),i.refreshComponent()},i.refreshComponent=function(){c.refreshItems&&c.refreshItems(),c.sizeSearchInput&&c.sizeSearchInput()},i.removeChoice=function(s){if(c.isLocked(null,s))return!1;var n=c.selected[s],l={};return l[c.parserResult.itemName]=n,c.selected.splice(s,1),i.activeMatchIndex=-1,c.sizeSearchInput(),t(function(){c.onRemoveCallback(e,{$item:n,$model:c.parserResult.modelMapper(e,l)})}),i.updateModel(),!0},i.getPlaceholder=function(){if(!c.selected||!c.selected.length)return c.placeholder}}],controllerAs:"$selectMultiple",link:function(c,n,l,a){function r(e){return angular.isNumber(e.selectionStart)?e.selectionStart:e.value.length}function o(e){function s(){switch(e){case t.LEFT:return~g.activeMatchIndex?u:l;case t.RIGHT:return~g.activeMatchIndex&&a!==l?o:(p.activate(),!1);case t.BACKSPACE:return~g.activeMatchIndex?g.removeChoice(a)?u:a:l;case t.DELETE:return!!~g.activeMatchIndex&&(g.removeChoice(g.activeMatchIndex),a)}}var i=r(p.searchInput[0]),c=p.selected.length,n=0,l=c-1,a=g.activeMatchIndex,o=g.activeMatchIndex+1,u=g.activeMatchIndex-1,d=a;return!(i>0||p.search.length&&e==t.RIGHT)&&(p.close(),d=s(),p.selected.length&&d!==!1?g.activeMatchIndex=Math.min(l,Math.max(n,d)):g.activeMatchIndex=-1,!0)}function u(e){if(void 0===e||void 0===p.search)return!1;var t=e.filter(function(e){return void 0!==p.search.toUpperCase()&&void 0!==e&&e.toUpperCase()===p.search.toUpperCase()}).length>0;return t}function d(e,t){var s=-1;if(angular.isArray(e))for(var i=angular.copy(e),c=0;c<i.length;c++)if(void 0===p.tagging.fct)i[c]+" "+p.taggingLabel===t&&(s=c);else{var n=i[c];angular.isObject(n)&&(n.isTag=!0),angular.equals(n,t)&&(s=c)}return s}var p=a[0],h=c.ngModel=a[1],g=c.$selectMultiple;p.multiple=!0,p.focusInput=p.searchInput,h.$isEmpty=function(e){return!e||0===e.length},h.$parsers.unshift(function(){for(var e,t={},s=[],i=p.selected.length-1;i>=0;i--)t={},t[p.parserResult.itemName]=p.selected[i],e=p.parserResult.modelMapper(c,t),s.unshift(e);return s}),h.$formatters.unshift(function(e){var t,s=p.parserResult&&p.parserResult.source(c,{$select:{search:""}}),i={};if(!s)return e;var n=[],l=function(e,s){if(e&&e.length){for(var l=e.length-1;l>=0;l--){if(i[p.parserResult.itemName]=e[l],t=p.parserResult.modelMapper(c,i),p.parserResult.trackByExp){var a=/(\w*)\./.exec(p.parserResult.trackByExp),r=/\.([^\s]+)/.exec(p.parserResult.trackByExp);if(a&&a.length>0&&a[1]==p.parserResult.itemName&&r&&r.length>0&&t[r[1]]==s[r[1]])return n.unshift(e[l]),!0}if(angular.equals(t,s))return n.unshift(e[l]),!0}return!1}};if(!e)return n;for(var a=e.length-1;a>=0;a--)l(p.selected,e[a])||l(s,e[a])||n.unshift(e[a]);return n}),c.$watchCollection(function(){return h.$modelValue},function(e,t){t!=e&&(angular.isDefined(h.$modelValue)&&(h.$modelValue=null),g.refreshComponent())}),h.$render=function(){if(!angular.isArray(h.$viewValue)){if(!e(h.$viewValue))throw s("multiarr","Expected model value to be array but got '{0}'",h.$viewValue);h.$viewValue=[]}p.selected=h.$viewValue,g.refreshComponent(),c.$evalAsync()},c.$on("uis:select",function(e,t){if(!(p.selected.length>=p.limit)){p.selected.push(t);var s={};s[p.parserResult.itemName]=t,i(function(){p.onSelectCallback(c,{$item:t,$model:p.parserResult.modelMapper(c,s)})}),g.updateModel()}}),c.$on("uis:activate",function(){g.activeMatchIndex=-1}),c.$watch("$select.disabled",function(e,t){t&&!e&&p.sizeSearchInput()}),p.searchInput.on("keydown",function(e){var s=e.which;c.$apply(function(){var i=!1;t.isHorizontalMovement(s)&&(i=o(s)),i&&s!=t.TAB&&(e.preventDefault(),e.stopPropagation())})}),p.searchInput.on("keyup",function(e){if(t.isVerticalMovement(e.which)||c.$evalAsync(function(){p.activeIndex=p.taggingLabel===!1?-1:0}),p.tagging.isActivated&&p.search.length>0){if(e.which===t.TAB||t.isControl(e)||t.isFunctionKey(e)||e.which===t.ESC||t.isVerticalMovement(e.which))return;if(p.activeIndex=p.taggingLabel===!1?-1:0,p.taggingLabel===!1)return;var s,i,n,l,a=angular.copy(p.items),r=angular.copy(p.items),o=!1,h=-1;if(void 0!==p.tagging.fct){if(n=p.$filter("filter")(a,{isTag:!0}),n.length>0&&(l=n[0]),a.length>0&&l&&(o=!0,a=a.slice(1,a.length),r=r.slice(1,r.length)),s=p.tagging.fct(p.search),r.some(function(e){return angular.equals(e,s)})||p.selected.some(function(e){return angular.equals(e,s)}))return void c.$evalAsync(function(){p.activeIndex=0,p.items=a});s&&(s.isTag=!0)}else{if(n=p.$filter("filter")(a,function(e){return e.match(p.taggingLabel)}),n.length>0&&(l=n[0]),i=a[0],void 0!==i&&a.length>0&&l&&(o=!0,a=a.slice(1,a.length),r=r.slice(1,r.length)),s=p.search+" "+p.taggingLabel,d(p.selected,p.search)>-1)return;if(u(r.concat(p.selected)))return void(o&&(a=r,c.$evalAsync(function(){p.activeIndex=0,p.items=a})));if(u(r))return void(o&&(p.items=r.slice(1,r.length)))}o&&(h=d(p.selected,s)),h>-1?a=a.slice(h+1,a.length-1):(a=[],s&&a.push(s),a=a.concat(r)),c.$evalAsync(function(){if(p.activeIndex=0,p.items=a,p.isGrouped){var e=s?a.slice(1):a;p.setItemsFn(e),s&&(p.items.unshift(s),p.groups.unshift({name:"",items:[s],tagging:!0}))}})}}),p.searchInput.on("blur",function(){i(function(){g.activeMatchIndex=-1})})}}}]),i.directive("uiSelectNoChoice",["uiSelectConfig",function(e){return{restrict:"EA",require:"^uiSelect",replace:!0,transclude:!0,templateUrl:function(t){t.addClass("ui-select-no-choice");var s=t.parent().attr("theme")||e.theme;return s+"/no-choice.tpl.html"}}}]),i.directive("uiSelectSingle",["$timeout","$compile",function(s,i){return{restrict:"EA",require:["^uiSelect","^ngModel"],link:function(c,n,l,a){var r=a[0],o=a[1];o.$parsers.unshift(function(t){if(e(t))return t;var s,i={};return i[r.parserResult.itemName]=t,s=r.parserResult.modelMapper(c,i)}),o.$formatters.unshift(function(t){if(e(t))return t;var s,i=r.parserResult&&r.parserResult.source(c,{$select:{search:""}}),n={};if(i){var l=function(e){return n[r.parserResult.itemName]=e,s=r.parserResult.modelMapper(c,n),s===t};if(r.selected&&l(r.selected))return r.selected;for(var a=i.length-1;a>=0;a--)if(l(i[a]))return i[a]}return t}),c.$watch("$select.selected",function(e){o.$viewValue!==e&&o.$setViewValue(e)}),o.$render=function(){r.selected=o.$viewValue},c.$on("uis:select",function(t,i){r.selected=i;var n={};n[r.parserResult.itemName]=i,s(function(){r.onSelectCallback(c,{$item:i,$model:e(i)?i:r.parserResult.modelMapper(c,n)})})}),c.$on("uis:close",function(e,t){s(function(){r.focusser.prop("disabled",!1),t||r.focusser[0].focus()},0,!1)}),c.$on("uis:activate",function(){u.prop("disabled",!0)});var u=angular.element("<input ng-disabled='$select.disabled' class='ui-select-focusser ui-select-offscreen' type='text' id='{{ $select.focusserId }}' aria-label='{{ $select.focusserTitle }}' aria-haspopup='true' role='button' />");i(u)(c),r.focusser=u,r.focusInput=u,n.parent().append(u),u.bind("focus",function(){c.$evalAsync(function(){r.focus=!0})}),u.bind("blur",function(){c.$evalAsync(function(){r.focus=!1})}),u.bind("keydown",function(e){return e.which===t.BACKSPACE&&r.backspaceReset!==!1?(e.preventDefault(),e.stopPropagation(),r.select(void 0),void c.$apply()):void(e.which===t.TAB||t.isControl(e)||t.isFunctionKey(e)||e.which===t.ESC||(e.which!=t.DOWN&&e.which!=t.UP&&e.which!=t.ENTER&&e.which!=t.SPACE||(e.preventDefault(),e.stopPropagation(),r.activate()),c.$digest()))}),u.bind("keyup input",function(e){e.which===t.TAB||t.isControl(e)||t.isFunctionKey(e)||e.which===t.ESC||e.which==t.ENTER||e.which===t.BACKSPACE||(r.activate(u.val()),u.val(""),c.$digest())})}}}]),i.directive("uiSelectSort",["$timeout","uiSelectConfig","uiSelectMinErr",function(e,t,s){return{require:["^^uiSelect","^ngModel"],link:function(t,i,c,n){if(null===t[c.uiSelectSort])throw s("sort","Expected a list to sort");var l=n[0],a=n[1],r=angular.extend({axis:"horizontal"},t.$eval(c.uiSelectSortOptions)),o=r.axis,u="dragging",d="dropping",p="dropping-before",h="dropping-after";t.$watch(function(){return l.sortable},function(e){e?i.attr("draggable",!0):i.removeAttr("draggable")}),i.on("dragstart",function(e){i.addClass(u),(e.dataTransfer||e.originalEvent.dataTransfer).setData("text",t.$index.toString())}),i.on("dragend",function(){v(u)});var g,f=function(e,t){this.splice(t,0,this.splice(e,1)[0])},v=function(e){angular.forEach(l.$element.querySelectorAll("."+e),function(t){angular.element(t).removeClass(e)})},m=function(e){e.preventDefault();var t="vertical"===o?e.offsetY||e.layerY||(e.originalEvent?e.originalEvent.offsetY:0):e.offsetX||e.layerX||(e.originalEvent?e.originalEvent.offsetX:0);t<this["vertical"===o?"offsetHeight":"offsetWidth"]/2?(v(h),i.addClass(p)):(v(p),i.addClass(h))},$=function(t){t.preventDefault();var s=parseInt((t.dataTransfer||t.originalEvent.dataTransfer).getData("text"),10);e.cancel(g),g=e(function(){b(s)},20)},b=function(e){var s=t.$eval(c.uiSelectSort),n=s[e],l=null;l=i.hasClass(p)?e<t.$index?t.$index-1:t.$index:e<t.$index?t.$index:t.$index+1,f.apply(s,[e,l]),a.$setViewValue(Date.now()),t.$apply(function(){t.$emit("uiSelectSort:change",{array:s,item:n,from:e,to:l})}),v(d),v(p),v(h),i.off("drop",$)};i.on("dragenter",function(){i.hasClass(u)||(i.addClass(d),i.on("dragover",m),i.on("drop",$))}),i.on("dragleave",function(e){e.target==i&&(v(d),v(p),v(h),i.off("dragover",m),i.off("drop",$))})}}}]),i.factory("$$uisDebounce",["$timeout",function(e){return function(t,s){var i;return function(){var c=this,n=Array.prototype.slice.call(arguments);i&&e.cancel(i),i=e(function(){t.apply(c,n)},s)}}}]),i.directive("uisOpenClose",["$parse","$timeout",function(e,t){return{restrict:"A",require:"uiSelect",link:function(s,i,c,n){n.onOpenCloseCallback=e(c.uisOpenClose),s.$watch("$select.open",function(e,i){e!==i&&t(function(){n.onOpenCloseCallback(s,{isOpen:e});
})})}}}]),i.service("uisRepeatParser",["uiSelectMinErr","$parse",function(e,t){var s=this;s.parse=function(s){var i;if(i=s.match(/^\s*(?:([\s\S]+?)\s+as\s+)?(?:([\$\w][\$\w]*)|(?:\(\s*([\$\w][\$\w]*)\s*,\s*([\$\w][\$\w]*)\s*\)))\s+in\s+(\s*[\s\S]+?)?(?:\s+track\s+by\s+([\s\S]+?))?\s*$/),!i)throw e("iexp","Expected expression in form of '_item_ in _collection_[ track by _id_]' but got '{0}'.",s);var c=i[5],n="";if(i[3]){c=i[5].replace(/(^\()|(\)$)/g,"");var l=i[5].match(/^\s*(?:[\s\S]+?)(?:[^\|]|\|\|)+([\s\S]*)\s*$/);l&&l[1].trim()&&(n=l[1],c=c.replace(n,""))}return{itemName:i[4]||i[2],keyName:i[3],source:t(c),filters:n,trackByExp:i[6],modelMapper:t(i[1]||i[4]||i[2]),repeatExpression:function(e){var t=this.itemName+" in "+(e?"$group.items":"$select.items");return this.trackByExp&&(t+=" track by "+this.trackByExp),t}}},s.getGroupNgRepeatExpression=function(){return"$group in $select.groups track by $group.name"}}])}(),angular.module("ui.select").run(["$templateCache",function(e){e.put("bootstrap/choices.tpl.html",'<ul class="ui-select-choices ui-select-choices-content ui-select-dropdown dropdown-menu" ng-show="$select.open && $select.items.length > 0"><li class="ui-select-choices-group" id="ui-select-choices-{{ $select.generatedId }}"><div class="divider" ng-show="$select.isGrouped && $index > 0"></div><div ng-show="$select.isGrouped" class="ui-select-choices-group-label dropdown-header" ng-bind="$group.name"></div><div ng-attr-id="ui-select-choices-row-{{ $select.generatedId }}-{{$index}}" class="ui-select-choices-row" ng-class="{active: $select.isActive(this), disabled: $select.isDisabled(this)}" role="option"><span class="ui-select-choices-row-inner"></span></div></li></ul>'),e.put("bootstrap/match-multiple.tpl.html",'<span class="ui-select-match"><span ng-repeat="$item in $select.selected track by $index"><span class="ui-select-match-item btn btn-default btn-xs" tabindex="-1" type="button" ng-disabled="$select.disabled" ng-click="$selectMultiple.activeMatchIndex = $index;" ng-class="{\'btn-primary\':$selectMultiple.activeMatchIndex === $index, \'select-locked\':$select.isLocked(this, $index)}" ui-select-sort="$select.selected"><span class="close ui-select-match-close" ng-hide="$select.disabled" ng-click="$selectMultiple.removeChoice($index)">&nbsp;&times;</span> <span uis-transclude-append=""></span></span></span></span>'),e.put("bootstrap/match.tpl.html",'<div class="ui-select-match" ng-hide="$select.open && $select.searchEnabled" ng-disabled="$select.disabled" ng-class="{\'btn-default-focus\':$select.focus}"><span tabindex="-1" class="btn btn-default form-control ui-select-toggle" aria-label="{{ $select.baseTitle }} activate" ng-disabled="$select.disabled" ng-click="$select.activate()" style="outline: 0;"><span ng-show="$select.isEmpty()" class="ui-select-placeholder text-muted">{{$select.placeholder}}</span> <span ng-hide="$select.isEmpty()" class="ui-select-match-text pull-left" ng-class="{\'ui-select-allow-clear\': $select.allowClear && !$select.isEmpty()}" ng-transclude=""></span> <i class="caret pull-right" ng-click="$select.toggle($event)"></i> <a ng-show="$select.allowClear && !$select.isEmpty() && ($select.disabled !== true)" aria-label="{{ $select.baseTitle }} clear" style="margin-right: 10px" ng-click="$select.clear($event)" class="btn btn-xs btn-link pull-right"><i class="glyphicon glyphicon-remove" aria-hidden="true"></i></a></span></div>'),e.put("bootstrap/no-choice.tpl.html",'<ul class="ui-select-no-choice dropdown-menu" ng-show="$select.items.length == 0"><li ng-transclude=""></li></ul>'),e.put("bootstrap/select-multiple.tpl.html",'<div class="ui-select-container ui-select-multiple ui-select-bootstrap dropdown form-control" ng-class="{open: $select.open}"><div><div class="ui-select-match"></div><input type="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" class="ui-select-search input-xs" placeholder="{{$selectMultiple.getPlaceholder()}}" ng-disabled="$select.disabled" ng-click="$select.activate()" ng-model="$select.search" role="combobox" aria-expanded="{{$select.open}}" aria-label="{{$select.baseTitle}}" ng-class="{\'spinner\': $select.refreshing}" ondrop="return false;"></div><div class="ui-select-choices"></div><div class="ui-select-no-choice"></div></div>'),e.put("bootstrap/select.tpl.html",'<div class="ui-select-container ui-select-bootstrap dropdown" ng-class="{open: $select.open}"><div class="ui-select-match"></div><span ng-show="$select.open && $select.refreshing && $select.spinnerEnabled" class="ui-select-refreshing {{$select.spinnerClass}}"></span> <input type="search" autocomplete="off" tabindex="-1" aria-expanded="true" aria-label="{{ $select.baseTitle }}" aria-owns="ui-select-choices-{{ $select.generatedId }}" class="form-control ui-select-search" ng-class="{ \'ui-select-search-hidden\' : !$select.searchEnabled }" placeholder="{{$select.placeholder}}" ng-model="$select.search" ng-show="$select.open"><div class="ui-select-choices"></div><div class="ui-select-no-choice"></div></div>'),e.put("select2/choices.tpl.html",'<ul tabindex="-1" class="ui-select-choices ui-select-choices-content select2-results"><li class="ui-select-choices-group" ng-class="{\'select2-result-with-children\': $select.choiceGrouped($group) }"><div ng-show="$select.choiceGrouped($group)" class="ui-select-choices-group-label select2-result-label" ng-bind="$group.name"></div><ul id="ui-select-choices-{{ $select.generatedId }}" ng-class="{\'select2-result-sub\': $select.choiceGrouped($group), \'select2-result-single\': !$select.choiceGrouped($group) }"><li role="option" ng-attr-id="ui-select-choices-row-{{ $select.generatedId }}-{{$index}}" class="ui-select-choices-row" ng-class="{\'select2-highlighted\': $select.isActive(this), \'select2-disabled\': $select.isDisabled(this)}"><div class="select2-result-label ui-select-choices-row-inner"></div></li></ul></li></ul>'),e.put("select2/match-multiple.tpl.html",'<span class="ui-select-match"><li class="ui-select-match-item select2-search-choice" ng-repeat="$item in $select.selected track by $index" ng-class="{\'select2-search-choice-focus\':$selectMultiple.activeMatchIndex === $index, \'select2-locked\':$select.isLocked(this, $index)}" ui-select-sort="$select.selected"><span uis-transclude-append=""></span> <a href="javascript:;" class="ui-select-match-close select2-search-choice-close" ng-click="$selectMultiple.removeChoice($index)" tabindex="-1"></a></li></span>'),e.put("select2/match.tpl.html",'<a class="select2-choice ui-select-match" ng-class="{\'select2-default\': $select.isEmpty()}" ng-click="$select.toggle($event)" aria-label="{{ $select.baseTitle }} select"><span ng-show="$select.isEmpty()" class="select2-chosen">{{$select.placeholder}}</span> <span ng-hide="$select.isEmpty()" class="select2-chosen" ng-transclude=""></span> <abbr ng-if="$select.allowClear && !$select.isEmpty()" class="select2-search-choice-close" ng-click="$select.clear($event)"></abbr> <span class="select2-arrow ui-select-toggle"><b></b></span></a>'),e.put("select2/no-choice.tpl.html",'<div class="ui-select-no-choice dropdown" ng-show="$select.items.length == 0"><div class="dropdown-content"><div data-selectable="" ng-transclude=""></div></div></div>'),e.put("select2/select-multiple.tpl.html",'<div class="ui-select-container ui-select-multiple select2 select2-container select2-container-multi" ng-class="{\'select2-container-active select2-dropdown-open open\': $select.open, \'select2-container-disabled\': $select.disabled}"><ul class="select2-choices"><span class="ui-select-match"></span><li class="select2-search-field"><input type="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" role="combobox" aria-expanded="true" aria-owns="ui-select-choices-{{ $select.generatedId }}" aria-label="{{ $select.baseTitle }}" aria-activedescendant="ui-select-choices-row-{{ $select.generatedId }}-{{ $select.activeIndex }}" class="select2-input ui-select-search" placeholder="{{$selectMultiple.getPlaceholder()}}" ng-disabled="$select.disabled" ng-hide="$select.disabled" ng-model="$select.search" ng-click="$select.activate()" style="width: 34px;" ondrop="return false;"></li></ul><div class="ui-select-dropdown select2-drop select2-with-searchbox select2-drop-active" ng-class="{\'select2-display-none\': !$select.open || $select.items.length === 0}"><div class="ui-select-choices"></div></div></div>'),e.put("select2/select.tpl.html",'<div class="ui-select-container select2 select2-container" ng-class="{\'select2-container-active select2-dropdown-open open\': $select.open, \'select2-container-disabled\': $select.disabled, \'select2-container-active\': $select.focus, \'select2-allowclear\': $select.allowClear && !$select.isEmpty()}"><div class="ui-select-match"></div><div class="ui-select-dropdown select2-drop select2-with-searchbox select2-drop-active" ng-class="{\'select2-display-none\': !$select.open}"><div class="search-container" ng-class="{\'ui-select-search-hidden\':!$select.searchEnabled, \'select2-search\':$select.searchEnabled}"><input type="search" autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false" ng-class="{\'select2-active\': $select.refreshing}" role="combobox" aria-expanded="true" aria-owns="ui-select-choices-{{ $select.generatedId }}" aria-label="{{ $select.baseTitle }}" class="ui-select-search select2-input" ng-model="$select.search"></div><div class="ui-select-choices"></div><div class="ui-select-no-choice"></div></div></div>'),e.put("selectize/choices.tpl.html",'<div ng-show="$select.open" class="ui-select-choices ui-select-dropdown selectize-dropdown" ng-class="{\'single\': !$select.multiple, \'multi\': $select.multiple}"><div class="ui-select-choices-content selectize-dropdown-content"><div class="ui-select-choices-group optgroup"><div ng-show="$select.isGrouped" class="ui-select-choices-group-label optgroup-header" ng-bind="$group.name"></div><div role="option" class="ui-select-choices-row" ng-class="{active: $select.isActive(this), disabled: $select.isDisabled(this)}"><div class="option ui-select-choices-row-inner" data-selectable=""></div></div></div></div></div>'),e.put("selectize/match-multiple.tpl.html",'<div class="ui-select-match" data-value="" ng-repeat="$item in $select.selected track by $index" ng-click="$selectMultiple.activeMatchIndex = $index;" ng-class="{\'active\':$selectMultiple.activeMatchIndex === $index}" ui-select-sort="$select.selected"><span class="ui-select-match-item" ng-class="{\'select-locked\':$select.isLocked(this, $index)}"><span uis-transclude-append=""></span> <span class="remove ui-select-match-close" ng-hide="$select.disabled" ng-click="$selectMultiple.removeChoice($index)">&times;</span></span></div>'),e.put("selectize/match.tpl.html",'<div ng-hide="$select.searchEnabled && ($select.open || $select.isEmpty())" class="ui-select-match"><span ng-show="!$select.searchEnabled && ($select.isEmpty() || $select.open)" class="ui-select-placeholder text-muted">{{$select.placeholder}}</span> <span ng-hide="$select.isEmpty() || $select.open" ng-transclude=""></span></div>'),e.put("selectize/no-choice.tpl.html",'<div class="ui-select-no-choice selectize-dropdown" ng-show="$select.items.length == 0"><div class="selectize-dropdown-content"><div data-selectable="" ng-transclude=""></div></div></div>'),e.put("selectize/select-multiple.tpl.html",'<div class="ui-select-container selectize-control multi plugin-remove_button" ng-class="{\'open\': $select.open}"><div class="selectize-input" ng-class="{\'focus\': $select.open, \'disabled\': $select.disabled, \'selectize-focus\' : $select.focus}" ng-click="$select.open && !$select.searchEnabled ? $select.toggle($event) : $select.activate()"><div class="ui-select-match"></div><input type="search" autocomplete="off" tabindex="-1" class="ui-select-search" ng-class="{\'ui-select-search-hidden\':!$select.searchEnabled}" placeholder="{{$selectMultiple.getPlaceholder()}}" ng-model="$select.search" ng-disabled="$select.disabled" aria-expanded="{{$select.open}}" aria-label="{{ $select.baseTitle }}" ondrop="return false;"></div><div class="ui-select-choices"></div><div class="ui-select-no-choice"></div></div>'),e.put("selectize/select.tpl.html",'<div class="ui-select-container selectize-control single" ng-class="{\'open\': $select.open}"><div class="selectize-input" ng-class="{\'focus\': $select.open, \'disabled\': $select.disabled, \'selectize-focus\' : $select.focus}" ng-click="$select.open && !$select.searchEnabled ? $select.toggle($event) : $select.activate()"><div class="ui-select-match"></div><input type="search" autocomplete="off" tabindex="-1" class="ui-select-search ui-select-toggle" ng-class="{\'ui-select-search-hidden\':!$select.searchEnabled}" ng-click="$select.toggle($event)" placeholder="{{$select.placeholder}}" ng-model="$select.search" ng-hide="!$select.isEmpty() && !$select.open" ng-disabled="$select.disabled" aria-label="{{ $select.baseTitle }}"></div><div class="ui-select-choices"></div><div class="ui-select-no-choice"></div></div>')}]);
//# sourceMappingURL=select.min.js.map

MetronicApp.constant("globe", {
  'DOMAIN_NAME' : '//'+globalIP+context+'/public', 
});

MetronicApp.controller('AppController', ['$scope','$http','$filter','globe', function($scope,$http,$filter,GLOBAL) {


    $scope.logoName="GPSVTS";
    $scope.groupId=0;

        $http({
            method : "GET",
            url : GLOBAL.DOMAIN_NAME+'/getVehicleLocations'
        }).then(function mySuccess(response) {
                 
            console.log(response.data);

            $scope.vehiDatas = response.data[$scope.groupId];
            $scope.vehiData  = response.data;
            $scope.repData   = response.data[$scope.groupId].vehicleLocations;
            console.log($scope.vehiData);

            $scope.selGrpName = $scope.vehiData[0].group;

            $scope.group_list=[];

         for(i=0;i<$scope.vehiData.length;i++){
           $scope.group_list.push({'grpName' : $scope.vehiData[i].group});
         } 



        }, function myError(response) {
            $scope.myWelcome = response.statusText;
        });


        var myLatLng = {lat: -25.363, lng: 131.044};

        var map = new google.maps.Map(document.getElementById('gmap_geocoding'), {
          zoom: 4,
          center: myLatLng
        });

        var marker = new google.maps.Marker({
          position: myLatLng,
          map: map,
          title: 'Hello World!'
        });


     var markerSearch = new google.maps.Marker({});
     
    var input_value   =  document.getElementById('gmap_geocoding_address');
    var sbox          =  new google.maps.places.SearchBox(input_value);
  // search box function
    sbox.addListener('places_changed', function() {
      markerSearch.setMap(null);
      var places = sbox.getPlaces();
      markerSearch = new google.maps.Marker({
        position: new google.maps.LatLng(places[0].geometry.location.lat(), places[0].geometry.location.lng()),
        animation: google.maps.Animation.BOUNCE,
        map: map,
    
      });
    //  console.log(' lat lan  '+places[0].geometry.location.lat(), places[0].geometry.location.lng())
      map.setCenter(new google.maps.LatLng(places[0].geometry.location.lat(), places[0].geometry.location.lng()));
      map.setZoom(13);
    });

     $scope.groupSelection = function(group){

        console.log(group);

         $scope.selGrpName  = group.grpName;
           $scope.groupName = group.grpName;

            for(var i=0;i<$scope.vehiData.length;i++){
               if($scope.vehiData[i].group==$scope.groupName){
                  $scope.groupId = $scope.vehiData[i].rowId;
               }
            }

           console.log($scope.groupId);
        // $scope.groupId=rowId;
        // console.log(group.grpName);
        // console.log(rowId.rowIds);

          $http({
            method : "GET",
            url :GLOBAL.DOMAIN_NAME+'/getVehicleLocations?group='+$scope.groupName,
          }).then(function mySuccess(response) {

                $scope.vehiDatas = response.data[$scope.groupId];
                $scope.vehiData  = response.data;
                $scope.repData   = response.data[$scope.groupId].vehicleLocations;
                console.log($scope.vehiDatas);

        }, function myError(response) {
          
        });
      }; 

    var indVehicle;

    $scope.genericFunction = function(vehId){

      console.log(vehId);

      console.log('generic..');
   
      indVehicle = $filter('filter')($scope.vehiData[$scope.groupId].vehicleLocations, { vehicleId:vehId});
      $scope.individualVehicle = indVehicle[0];

      var maps = new google.maps.Map(document.getElementById('gmap_geocoding'), {
          zoom: 13,
          center: new google.maps.LatLng(indVehicle[0].latitude, indVehicle[0].longitude),
        });

         var markerSearchs = new google.maps.Marker({});
    
        markerSearchs = new google.maps.Marker({
        position: new google.maps.LatLng(indVehicle[0].latitude, indVehicle[0].longitude),
        animation: google.maps.Animation.BOUNCE,
        map: maps,
    
      });

    };


    function distVal(newVal){

      console.log(newVal);

      var retsArr=[];

      for(var i=0; i<newVal.length; i++){
        //console.log(newVal[i].shortName);
        retsArr.push( { "distance" :newVal[i].distanceToday,"date" : newVal[i].shortName});
      }

    var newArrs = [];
        newArrs = retsArr;
     
    Highcharts.chart('containerss', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'World\'s largest cities per 2014'
    },
    subtitle: {
        text: 'Source: <a href="http://en.wikipedia.org/wiki/List_of_cities_proper_by_population">Wikipedia</a>'
    },
    xAxis: {
        type: 'category',
        labels: {
            rotation: -45,
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    },
    yAxis: {
        min: 0,
        title: {
            text: 'Population (millions)'
        }
    },
    legend: {
        enabled: false
    },
    tooltip: {
        pointFormat: 'Population in 2008: <b>{point.y:.1f} millions</b>'
    },
    series: [{
        name: 'Population',
        data: [
            ['Shanghai', 23.7],
            ['Lagos', 16.1],
            ['Istanbul', 14.2],
            ['Karachi', 14.0],
            ['Mumbai', 12.5],
            ['Moscow', 12.1],
            ['São Paulo', 11.8],
            ['Beijing', 11.7],
            ['Guangzhou', 11.1],
            ['Delhi', 11.1],
            ['Shenzhen', 10.5],
            ['Seoul', 10.4],
            ['Jakarta', 10.0],
            ['Kinshasa', 9.3],
            ['Tianjin', 9.3],
            ['Tokyo', 9.0],
            ['Cairo', 8.9],
            ['Dhaka', 8.9],
            ['Mexico City', 8.9],
            ['Lima', 8.9]
        ],
        dataLabels: {
            enabled: true,
            rotation: -90,
            color: '#FFFFFF',
            align: 'right',
            format: '{point.y:.1f}', // one decimal
            y: 10, // 10 pixels down from the top
            style: {
                fontSize: '13px',
                fontFamily: 'Verdana, sans-serif'
            }
        }
    }]
});


}


function distChart(){   
 
 var newArr = []; 

        $http({
            method : "GET",
            url :'http://188.166.244.126:9000/getExecutiveReport?groupId=JCT:SMP&fromDate=2018-01-29&toDate=2018-02-04&userId=JCT',
          }).then(function mySuccess(response) {

            console.log(response.data);

            newArr = response.data.execReportData;  

            //console.log(newArr);

            distVal(newArr);


        }, function myError(response) {
          
        });

 /* var newArr =[{
      "date" : "2012-01-01",
      "distance" : 227,
      "townName" : "New York",
      "townName2" : "New York",
      "townSize" : 25,
      "latitude" : 40.71,
      "duration" : 408
  }];
*/


}

distChart();


Highcharts.chart('containerx', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Efficiency Optimization by Branch'
    },
    xAxis: {
        categories: [
            'Seattle HQ',
            'San Francisco',
            'Tokyo'
        ]
    },
    yAxis: [{
        min: 0,
        title: {
            text: 'Employees'
        }
    }, {
        title: {
            text: 'Profit (millions)'
        },
        opposite: true
    }],
    legend: {
        shadow: false
    },
    tooltip: {
        shared: true
    },
    plotOptions: {
        column: {
            grouping: false,
            shadow: false,
            borderWidth: 0
        }
    },
    series: [{
        name: 'Employees',
        color: 'rgba(165,170,217,1)',
        data: [150, 73, 20],
        pointPadding: 0.3,
        pointPlacement: -0.2
    }, {
        name: 'Employees Optimized',
        color: 'rgba(126,86,134,.9)',
        data: [140, 90, 40],
        pointPadding: 0.4,
        pointPlacement: -0.2
    }, {
        name: 'Profit',
        color: 'rgba(248,161,63,1)',
        data: [183.6, 178.8, 198.5],
        tooltip: {
            valuePrefix: '$',
            valueSuffix: ' M'
        },
        pointPadding: 0.3,
        pointPlacement: 0.2,
        yAxis: 1
    }, {
        name: 'Profit Optimized',
        color: 'rgba(186,60,61,.9)',
        data: [203.6, 198.8, 208.5],
        tooltip: {
            valuePrefix: '$',
            valueSuffix: ' M'
        },
        pointPadding: 0.4,
        pointPlacement: 0.2,
        yAxis: 1
    }]
});


     
   
}]);


