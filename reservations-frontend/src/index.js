import React from 'react'
import ReactDOM from 'react-dom'
import {Provider} from 'react-redux'
import {PersistGate} from 'redux-persist/integration/react'
import history from './history'
import configurationStore from './store/configureStore'
import {HashRouter} from 'react-router-dom'
import App from './App'

const {store, persistor} = configurationStore(history)
ReactDOM.render(
    <Provider store={store}>
        <PersistGate loading={null} persistor={persistor}>
            <HashRouter>
                <App/>
            </HashRouter>
        </PersistGate>
    </Provider>,
    document.getElementById('root')
)
/*const {store, persistor} = configurationStore(history)
ReactDOM.render(
    <Provider store={store}>
        <ConnectedRouter history={history}>
            <PersistGate loading={null} persistor={persistor}>
                <App/>
            </PersistGate>
        </ConnectedRouter>
    </Provider>,
    document.getElementById('root')
)*/