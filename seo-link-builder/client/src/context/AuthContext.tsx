import { createContext, useContext, useState, useEffect, type ReactNode } from 'react';
import api from '../api/api';
import type { User } from '../types';

interface AuthContextType {
  user: User | null;
  loading: boolean;
  login: (credentials: { email: string; password: string }) => Promise<void>;
  register: (credentials: { email: string; password: string }) => Promise<void>;
  logout: () => Promise<void>;
}

const AuthContext = createContext<AuthContextType | undefined>(undefined);

export const AuthProvider = ({ children }: { children: ReactNode }) => {
  const [user, setUser] = useState<User | null>(null);
  const [loading, setLoading] = useState(true);

  const checkUser = async () => {
    try {
      const { data } = await api.get('/auth.php?action=me');
      setUser(data.user);
    } catch (error) {
      console.error("Auth Check Error:", error);
      setUser(null);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    checkUser();
  }, []);

  const login = async (credentials: { email: string; password: string }) => {
    const { data } = await api.post('/auth.php?action=login', credentials);
    setUser(data.user);
  };

  const register = async (credentials: { email: string; password: string }) => {
    const { data } = await api.post('/auth.php?action=register', credentials);
    setUser(data.user);
  };

  const logout = async () => {
    await api.post('/auth.php?action=logout');
    setUser(null);
  };

  return (
    <AuthContext.Provider value={{ user, loading, login, register, logout }}>
      {children}
    </AuthContext.Provider>
  );
};

export const useAuth = () => {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within an AuthProvider');
  }
  return context;
};
