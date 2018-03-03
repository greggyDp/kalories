webpackJsonp([6],{cJGE:/*!******************************!*\
  !*** ./assets/js/filters.js ***!
  \******************************/
function(t,e,a){(function(t){t(function(){t(function(){t('input[name="createdAtFilter"]').datetimepicker().on("dp.hide",function(t){var e=window.location.pathname,a=new URLSearchParams(window.location.search),n=t.date.format("YYYY-MM-DD");if(a.has("createdAt")){if(a.get("createdAt")===n)return;a.set("createdAt",n)}else a.has("page"),a.append("createdAt",n);window.location.href=e+"?"+a.toString()})})})}).call(e,a(/*! jquery */"7t+N"))}},["cJGE"]);