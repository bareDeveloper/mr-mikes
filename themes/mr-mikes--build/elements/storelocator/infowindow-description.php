{{#location}}
<div class="loc-name">Mr Mikes {{name}}</div>
<div>{{address}}</div>

{{#if phone}}
<div>{{phone}}</div>
{{/if}}

<p class="loc-opening">Opening hours</p>
{{#if hours1}}
<div>{{hours1}}</div>
{{/if}} {{#if hours2}}
<div>{{hours2}}</div>
{{/if}} {{#if hours3}}
<div>{{hours3}}</div>
{{/if}} {{#if hours4}}
<div>{{hours4}}</div>
{{/if}} {{#if hours5}}
<div>{{hours5}}</div>
{{/if}} {{#if hours6}}
<div>{{hours6}}</div>
{{/if}} {{#if hours7}}
<div>{{hours7}}</div>
{{/if}}

<div class="loc-link">
    <a href="{{web}}">Details</a>
</div>
{{/location}}