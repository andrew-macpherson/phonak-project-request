


class PhonakApp extends React.Component{
	render(){
		return (
			<div>
				<ProjectType title="Print marketing" />
				<ProjectType title="Database marketing" />
				<ProjectType title="Digital marketing" />
				<ProjectType title="Events marketing " />
			</div>
		)
	}
}

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



var { BrowserRouter, Route } = ReactRouterDOM;

ReactDOM.render(
	<PhonakApp/>,
	document.getElementById('phonak_app')
);