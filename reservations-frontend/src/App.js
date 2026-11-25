import React from 'react'
import {Route} from 'react-router'
import Home from './containers/Home'
import Edit from './containers/EditReservation'
import Cancel from './containers/CancelarReservation'
import Congrats from './containers/Congrats'
import Brands from './components/Brands'
import KomenzaApp from "./components/KomenzaApp"


const App = () => <KomenzaApp>
    <Route exact path="/" component={Brands}/>
    <Route exact path="/reserva/:marca" component={Home}/>
    <Route exact path="/reserva/:marca/modificar/:token" component={Edit}/>
    <Route exact path="/reserva/:marca/cancelar/:token" component={Cancel}/>
    <Route exact path="/gracias/:token?" component={Congrats}/>
</KomenzaApp>
export default App