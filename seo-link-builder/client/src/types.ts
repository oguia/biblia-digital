export interface User {
  id: number;
  email: string;
  role: 'admin' | 'client';
  credits: number;
}

export interface Project {
  id: number;
  user_id: number;
  url: string;
  keywords: string;
  base_description: string;
  status: 'active' | 'paused' | 'completed';
  created_at: string;
}

export interface Target {
  id: number;
  url: string;
  type: string;
  status: 'pending' | 'active' | 'blacklisted';
}

export interface Submission {
  id: number;
  project_id: number;
  target_id: number;
  status: 'pending' | 'processing' | 'completed' | 'failed';
  live_url?: string;
  ai_generated_content?: string;
  created_at: string;
}
