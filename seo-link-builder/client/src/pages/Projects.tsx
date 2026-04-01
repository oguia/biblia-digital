import { useState, useEffect } from 'react';
import api from '../api/api';
import type { Project } from '../types';
import { Plus, Trash2 } from 'lucide-react';
import { useTranslation } from 'react-i18next';

const Projects = () => {
  const [projects, setProjects] = useState<Project[]>([]);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [newProject, setNewProject] = useState({ url: '', keywords: '', description: '' });
  const { t } = useTranslation();

  useEffect(() => {
    fetchProjects();
  }, []);

  const fetchProjects = async () => {
    try {
      const { data } = await api.get('/projects.php');
      setProjects(data.projects);
    } catch (err) {
      console.error(err);
    }
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      await api.post('/projects.php', newProject);
      setIsModalOpen(false);
      fetchProjects();
      setNewProject({ url: '', keywords: '', description: '' });
    } catch (err) {
      alert('Failed to create project');
    }
  };

  const handleDelete = async (id: number) => {
    if (!confirm('Are you sure?')) return;
    try {
      await api.delete(`/projects.php?id=${id}`);
      fetchProjects();
    } catch (err) {
      alert('Failed to delete');
    }
  };

  return (
    <div className="space-y-6">
      <div className="flex items-center justify-between">
        <h1 className="text-2xl font-bold text-gray-900">{t('projects.title')}</h1>
        <button onClick={() => setIsModalOpen(true)} className="flex items-center rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">
          <Plus className="mr-2 h-4 w-4" /> {t('projects.add_link')}
        </button>
      </div>

      <div className="overflow-hidden rounded-lg bg-white shadow">
        <table className="min-w-full divide-y divide-gray-200">
          <thead className="bg-gray-50">
            <tr>
              <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{t('projects.col_url')}</th>
              <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{t('projects.col_keywords')}</th>
              <th className="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">{t('projects.col_status')}</th>
              <th className="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">{t('projects.col_actions')}</th>
            </tr>
          </thead>
          <tbody className="divide-y divide-gray-200 bg-white">
            {projects.map((project) => (
              <tr key={project.id}>
                <td className="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{project.url}</td>
                <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{project.keywords}</td>
                <td className="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                  <span className="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">{project.status}</span>
                </td>
                <td className="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                  <button onClick={() => handleDelete(project.id)} className="text-red-600 hover:text-red-900">
                    <Trash2 className="h-5 w-5" />
                  </button>
                </td>
              </tr>
            ))}
            {projects.length === 0 && (
              <tr>
                <td colSpan={4} className="px-6 py-4 text-center text-sm text-gray-500">{t('projects.no_projects')}</td>
              </tr>
            )}
          </tbody>
        </table>
      </div>

      {/* Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-gray-600 bg-opacity-50">
          <div className="w-full max-w-lg rounded-lg bg-white p-6 shadow-xl">
            <h3 className="mb-4 text-lg font-bold text-gray-900">{t('projects.modal_title')}</h3>
            <form onSubmit={handleSubmit} className="space-y-4">
              <div>
                <label className="block text-sm font-medium text-gray-700">{t('projects.label_url')}</label>
                <input
                  type="url"
                  value={newProject.url}
                  onChange={(e) => setNewProject({ ...newProject, url: e.target.value })}
                  className="mt-1 block w-full rounded border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white text-gray-900"
                  required
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700">{t('projects.label_keywords')}</label>
                <input
                  type="text"
                  value={newProject.keywords}
                  onChange={(e) => setNewProject({ ...newProject, keywords: e.target.value })}
                  className="mt-1 block w-full rounded border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white text-gray-900"
                />
              </div>
              <div>
                <label className="block text-sm font-medium text-gray-700">{t('projects.label_description')}</label>
                <textarea
                  value={newProject.description}
                  onChange={(e) => setNewProject({ ...newProject, description: e.target.value })}
                  className="mt-1 block w-full rounded border-gray-300 p-2 shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-white text-gray-900"
                  rows={3}
                />
              </div>
              <div className="flex justify-end space-x-3">
                <button type="button" onClick={() => setIsModalOpen(false)} className="rounded border px-4 py-2 hover:bg-gray-100 text-gray-900">{t('projects.btn_cancel')}</button>
                <button type="submit" className="rounded bg-blue-600 px-4 py-2 text-white hover:bg-blue-700">{t('projects.btn_create')}</button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};

export default Projects;