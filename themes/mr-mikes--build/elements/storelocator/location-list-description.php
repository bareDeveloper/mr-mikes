{{#location}}
<li data-markerid="{{markerid}}">
	<div class="list-label">{{marker}}</div>
	<div class="list-details">
		<div class="list-content">

			<div class="loc-name">{{name}}</div>
			<div class="loc-addr">{{address}}</div>

			{{#if phone}}
			<div class="loc-phone">{{phone}}</div>
			{{/if}} {{#if distance}}
			<div class="loc-dist loc-default-dist">{{distance}} {{length}}</div>
			{{/if}} {{#if altdistance}}
			<div class="loc-dist loc-alt-dist">{{altdistance}} {{altlength}}</div>
			{{/if}} {{#if web}}
			<div class="loc-web">
				<a href="{{web}}">Details</a>
			</div>
			{{/if}}

			
			<div class="loc-directions">
				<a href="https://maps.google.com/maps?saddr={{origin}}&amp;daddr={{address}}" target="_blank">Directions</a>
			</div>

			{{#if open_table}}
				<div class="loc-reservation">
					<a data-featherlight=".storelocator__open-table--{{open_table}}" data-featherlight-variant="storelocator__open-table">
						Reservation
					</a>
				</div>

				<div class="storelocator__open-table storelocator__open-table--{{open_table}}">
					<iframe src="https://www.opentable.ca/widget/reservation/canvas?rid={{open_table}}&amp;type=standard&amp;theme=tall&amp;overlay=false&amp;domain=ca&amp;lang=en-CA&amp;r3abvariant=false&amp;r3uid=Xk-13oE9Uk&amp;newtab=false&amp;disablega=false" width="288" height="490" frameborder="0" scrolling="no" tabindex="-1"></iframe>
				</div>
			{{/if}}

		</div>
	</div>
</li>
{{/location}}