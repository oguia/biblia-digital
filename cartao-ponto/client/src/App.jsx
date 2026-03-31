import { useState, useEffect } from 'react';
import { HashRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import axios from 'axios';

// Pages
import Dashboard from './pages/Dashboard';
import Projects from './pages/Projects';
import History from './pages/History';
import Login from './pages/Login';
import Register from './pages/Register';
import Profile from './pages/Profile';
import Subscription from './pages/Subscription';
import AdminDashboard from './pages/AdminDashboard';

// Components
import Layout from './components/Layout';

// Set up axios interceptor to add token
axios.interceptors.request.use(config => {
  const token = localStorage.getItem('token');
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

function App() {
  const [user, setUser] = useState(null);
  const [loading, setLoading] = useState(true);

  // Set base URL depending on environment
  const apiUrl = import.meta.env.DEV ? 'http://localhost:8000/api' : './api';
  axios.defaults.baseURL = apiUrl;

  useEffect(() => {
    const checkAuth = async () => {
      const token = localStorage.getItem('token');
      if (token) {
        try {
          const res = await axios.get('/auth.php?action=me');
          setUser(res.data.user);
        } catch (err) {
          localStorage.removeItem('token');
        }
      }
      setLoading(false);
    };
    checkAuth();
  }, []);

  if (loading) {
    return <div className="h-screen w-full flex items-center justify-center">Carregando...</div>;
  }

  return (
    <Router>
      <Routes>
        <Route path="/login" element={!user ? <Login setUser={setUser} /> : <Navigate to="/" />} />
        <Route path="/register" element={!user ? <Register setUser={setUser} /> : <Navigate to="/" />} />

        <Route path="/" element={user ? <Layout user={user} setUser={setUser} /> : <Navigate to="/login" />}>
          <Route index element={
            user && user.is_admin !== 1 && (!user.plan_expires_at || new Date(user.plan_expires_at) < new Date())
              ? <Navigate to="/subscription" />
              : <Dashboard />
          } />
          <Route path="projects" element={<Projects />} />
          <Route path="history" element={<History />} />
          <Route path="profile" element={<Profile user={user} setUser={setUser} />} />
          <Route path="subscription" element={<Subscription user={user} setUser={setUser} />} />
          <Route path="admin" element={<AdminDashboard user={user} />} />
        </Route>
      </Routes>
    </Router>
  );
}

export default App;
