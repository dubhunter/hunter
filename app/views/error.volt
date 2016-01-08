{% extends 'layouts/core.volt' %}

{% block title %}{{ code }} {{ message }}{% endblock %}

{% block content %}
	<section>
		<div class="hero-unit clearfix">
			<img src="/img/monkey.png" class="pull-right">
			<h1>{{ code }} {{ message }}</h1>
			<p class="margin-top-xlarge">Either you are lost or something has gone terribly wrong.</p>
			<p>Why don't you head back <a href="{{ url(['for': 'home']) }}">home</a> and hope we don't meet again.</p>
		</div>
	</section>
{%  endblock %}
