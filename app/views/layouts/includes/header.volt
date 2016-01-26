{% set menuItems = {} %}

<nav class="navbar navbar-inverse navbar-static-top">
	<div class="container">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="{{ url(['for': 'home']) }}">Hunter Light</a>
		</div>
		{% if menuItems|length %}
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav">
					{% for name, item in menuItems %}
						<li{% if name == menuSelected %} class="active"{% endif %}>
							<a href="{{ item }}">{{ name }}</a>
						</li>
					{% endfor %}
				</ul>
			</div>
		{% endif %}
	</div>
</nav>
