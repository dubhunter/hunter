{% extends 'layouts/core.volt' %}

{% block content %}
	<div class="jumbotron">
		<div class="row">
			<div class="col-sm-4">
				<h2>Days</h2>
				<p>{{ days }}</p>
			</div>
			<div class="col-sm-4">
				<h2>Temp</h2>
				<p>{{ insideTemp }}&#x2109;  / {{ outsideTemp }}&#x2109;</p>
			</div>
			<div class="col-sm-4">
				<form action="{{ url(['for': 'home']) }}" method="post">

					<input type="hidden" name="{{ security.getTokenKey() }}" value="{{ security.getToken() }}">

					<h2>Color</h2>
					<select name="color" role="change-submit" class="input-lg">
						{% for c in colors %}
							<option value="{{ c }}"{% if c == color %} selected{% endif %}>{{ c|capitalize }}</option>
						{% endfor %}
					</select>
				</form>
			</div>
		</div>
	</div>
{%  endblock %}
