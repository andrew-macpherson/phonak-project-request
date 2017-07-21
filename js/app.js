

class ProjectType extends React.Component{

	render(){
		return (
			<div>
				<h3>{this.props.title}</h3>
				<a class="button" href="#">Start Project</a>
			</div>
		)
	}
}

class PhonakApp extends React.Component{
	render(){
		return (
			<div>
				<ProjectType title="Print marketing" />
				<ProjectType title="Database marking" />
				<ProjectType title="Digital marketing" />
				<ProjectType title="Events marketing " />
			</div>
		)
	}
}


ReactDOM.render(
	<PhonakApp />,
	document.getElementById('phonak_app')
);