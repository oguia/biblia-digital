import { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { Link, useNavigate } from 'react-router-dom';
import { useTranslation } from 'react-i18next';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');
  const { login } = useAuth();
  const navigate = useNavigate();
  const { t } = useTranslation();

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      await login({ email, password });
      navigate('/');
    } catch (err) {
      alert('Login failed');
    }
  };

  return (
    <div className="flex min-h-screen items-center justify-center bg-gray-100 text-gray-900">
      <div className="w-full max-w-md rounded-lg bg-white p-8 shadow-md">
        <h2 className="mb-6 text-center text-2xl font-bold text-gray-800">{t('auth.login_title')}</h2>
        <form onSubmit={handleSubmit} className="space-y-4">
          <div>
            <label className="block text-sm font-medium text-gray-700">{t('auth.email')}</label>
            <input
              type="email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              className="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white text-gray-900"
              required
            />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700">{t('auth.password')}</label>
            <input
              type="password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              className="mt-1 block w-full rounded-md border border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white text-gray-900"
              required
            />
          </div>
          <button
            type="submit"
            className="w-full rounded-md bg-blue-600 px-4 py-2 text-white hover:bg-blue-700 font-medium"
          >
            {t('auth.btn_signin')}
          </button>
        </form>
        <p className="mt-4 text-center text-sm text-gray-600">
          {t('auth.no_account')} <Link to="/register" className="text-blue-600 hover:underline">{t('auth.register_title')}</Link>
        </p>
      </div>
    </div>
  );
};

export default Login;