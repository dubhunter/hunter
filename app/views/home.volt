{% extends 'layouts/core.volt' %}

{% block content %}
	<div class="starter-template">
		<div class="row">
			<div class="col-sm-6">
				<p class="lead">Days</p>
				{{ days }}
			</div>
			<div class="col-sm-6">
				<p class="lead">Color</p>
				{{ color }}
			</div>
		</div>
	</div>
{%  endblock %}
