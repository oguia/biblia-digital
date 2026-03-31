import React from 'react';
import { useHashLocation } from 'wouter/use-hash-location';
import { Router, Route, Switch, Redirect } from 'wouter';
import Login from './pages/Login';
import Register from './pages/Register';
import Dashboard from './pages/Dashboard';
import PatientDashboard from './pages/PatientDashboard';

// Simple PrivateRoute wrapper component
const PrivateRoute = ({ component: Component, allowedRoles }) => {
  const userStr = localStorage.getItem('vouzelar_user');
  if (!userStr) return <Redirect to="/login" />;

  const user = JSON.parse(userStr);
  if (allowedRoles && !allowedRoles.includes(user.role)) {
     // Redirect to correct dashboard based on role
     return <Redirect to={user.role === 'patient' ? '/patient' : '/dashboard'} />;
  }

  return <Component />;
};

function App() {
  return (
    <Router hook={useHashLocation}>
      <div className="min-h-screen bg-[var(--color-background)]">
        <Switch>
          <Route path="/login" component={Login} />
          <Route path="/register" component={Register} />

          <Route path="/dashboard">
             {() => <PrivateRoute component={Dashboard} allowedRoles={['admin', 'caregiver']} />}
          </Route>

          <Route path="/patient">
             {() => <PrivateRoute component={PatientDashboard} allowedRoles={['patient', 'admin']} />}
          </Route>

          <Route path="/">
            <Redirect to="/login" />
          </Route>

          <Route>
            <div className="p-8 text-center text-red-500">404 - Página não encontrada</div>
          </Route>
        </Switch>
      </div>
    </Router>
  );
}

export default App;
