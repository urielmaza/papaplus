import React, { useState } from 'react';
import { BrowserRouter as Router, Route, Switch } from 'react-router-dom';
import Login from './Login';
import Panel from './Panel';

const App = () => {
  const [token, setToken] = useState(null);

  return (
    <Router>
      <Switch>
        <Route path="/" exact>
          <Login setToken={setToken} />
        </Route>
        <Route path="/panel">
          {token ? <Panel token={token} /> : <Login setToken={setToken} />}
        </Route>
      </Switch>
    </Router>
  );
};

export default App;
