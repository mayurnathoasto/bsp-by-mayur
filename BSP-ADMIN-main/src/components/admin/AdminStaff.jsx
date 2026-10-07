import { useEffect, useMemo, useState } from "react";
import toast from "react-hot-toast";
import {
  createAdminStaff,
  deleteAdminStaff,
  fetchAdminStaff,
  updateAdminStaff,
} from "../../services/api";

const emptyForm = {
  name: "",
  email: "",
  mobile: "",
  password: "",
  permissions: [],
  is_active: true,
};

function PermissionChecklist({ groups, selectedPermissions, onChange }) {
  const toggle = (permission) => {
    onChange(
      selectedPermissions.includes(permission)
        ? selectedPermissions.filter((item) => item !== permission)
        : [...selectedPermissions, permission],
    );
  };

  return (
    <div className="space-y-4">
      {groups.map((group) => (
        <div key={group.key} className="rounded-xl border border-gray-200 dark:border-gray-700 p-4">
          <div className="text-sm font-semibold text-gray-900 dark:text-white">{group.label}</div>
          <div className="mt-3 grid gap-3 sm:grid-cols-2">
            {group.permissions.map((permission) => (
              <label
                key={permission.key}
                className="flex items-start gap-3 rounded-lg border border-gray-100 dark:border-gray-700 bg-gray-50/70 dark:bg-gray-900/40 px-3 py-3"
              >
                <input
                  type="checkbox"
                  checked={selectedPermissions.includes(permission.key)}
                  onChange={() => toggle(permission.key)}
                  className="mt-0.5 h-4 w-4 rounded border-gray-300"
                />
                <div>
                  <div className="text-sm font-medium text-gray-900 dark:text-white">{permission.label}</div>
                  <div className="text-xs text-gray-400">{permission.key}</div>
                </div>
              </label>
            ))}
          </div>
        </div>
      ))}
    </div>
  );
}

export default function AdminStaff() {
  const [items, setItems] = useState([]);
  const [permissionGroups, setPermissionGroups] = useState([]);
  const [loading, setLoading] = useState(true);
  const [creating, setCreating] = useState(false);
  const [editingStaff, setEditingStaff] = useState(null);
  const [savingEdit, setSavingEdit] = useState(false);
  const [createForm, setCreateForm] = useState(emptyForm);

  const [editForm, setEditForm] = useState(emptyForm);

  const permissionsSummary = useMemo(() => {
    const map = {};
    permissionGroups.forEach((group) => {
      group.permissions.forEach((permission) => {
        map[permission.key] = permission.label;
      });
    });
    return map;
  }, [permissionGroups]);

  const loadStaff = async () => {
    try {
      setLoading(true);
      const { data } = await fetchAdminStaff();
      setItems(data.data || []);
      setPermissionGroups(data.permission_groups || []);
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to load staff users");
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadStaff();
  }, []);

  const handleCreate = async () => {
    if (!createForm.name.trim() || !createForm.email.trim() || !createForm.password.trim()) {
      toast.error("Name, email, and password are required");
      return;
    }

    try {
      setCreating(true);
      await createAdminStaff({
        ...createForm,
        name: createForm.name.trim(),
        email: createForm.email.trim(),
      });
      toast.success("Staff user created");
      setCreateForm(emptyForm);
      loadStaff();
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to create staff user");
    } finally {
      setCreating(false);
    }
  };

  const openEdit = (staff) => {
    setEditingStaff(staff);
    setEditForm({
      name: staff.name || "",
      email: staff.email || "",
      mobile: staff.mobile || "",
      password: "",
      permissions: staff.permissions || [],
      is_active: !!staff.is_active,
    });
  };

  const handleUpdate = async () => {
    if (!editingStaff) return;

    try {
      setSavingEdit(true);
      await updateAdminStaff(editingStaff.id, {
        ...editForm,
        name: editForm.name.trim(),
        email: editForm.email.trim(),
        password: editForm.password || undefined,
      });
      toast.success("Staff user updated");
      setEditingStaff(null);
      setEditForm(emptyForm);
      loadStaff();
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to update staff user");
    } finally {
      setSavingEdit(false);
    }
  };

  const handleDelete = async (staff) => {
    if (!window.confirm(`Delete ${staff.name}?`)) return;

    try {
      await deleteAdminStaff(staff.id);
      toast.success("Staff user deleted");
      loadStaff();
    } catch (error) {
      toast.error(error?.response?.data?.message || "Failed to delete staff user");
    }
  };

  return (
    <div className="space-y-6">
      <div>
        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">Staff Management</h1>
        <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
          Create staff users without public registration and assign exactly which admin modules and actions they can access.
        </p>
      </div>

      <section className="rounded-2xl border bg-white dark:bg-gray-800 p-6 shadow-sm">
        <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Create Staff User</h2>
        <div className="mt-4 grid gap-4 lg:grid-cols-2">
          <div className="space-y-4">
            <input
              type="text"
              placeholder="Full name"
              value={createForm.name}
              onChange={(event) => setCreateForm((prev) => ({ ...prev, name: event.target.value }))}
              className="w-full rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2.5 text-sm"
            />
            <input
              type="email"
              placeholder="Email"
              value={createForm.email}
              onChange={(event) => setCreateForm((prev) => ({ ...prev, email: event.target.value }))}
              className="w-full rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2.5 text-sm"
            />
            <input
              type="text"
              placeholder="Mobile"
              value={createForm.mobile}
              onChange={(event) => setCreateForm((prev) => ({ ...prev, mobile: event.target.value }))}
              className="w-full rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2.5 text-sm"
            />
            <input
              type="password"
              placeholder="Temporary password"
              value={createForm.password}
              onChange={(event) => setCreateForm((prev) => ({ ...prev, password: event.target.value }))}
              className="w-full rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2.5 text-sm"
            />
            <label className="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
              <input
                type="checkbox"
                checked={createForm.is_active}
                onChange={(event) => setCreateForm((prev) => ({ ...prev, is_active: event.target.checked }))}
                className="h-4 w-4 rounded border-gray-300"
              />
              Active account
            </label>
          </div>

          <PermissionChecklist
            groups={permissionGroups}
            selectedPermissions={createForm.permissions}
            onChange={(permissions) => setCreateForm((prev) => ({ ...prev, permissions }))}
          />
        </div>

        <div className="mt-5">
          <button
            onClick={handleCreate}
            disabled={creating}
            className="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60"
          >
            {creating ? "Creating..." : "Create Staff User"}
          </button>
        </div>
      </section>

      <section className="rounded-2xl border bg-white dark:bg-gray-800 shadow-sm overflow-hidden">
        <div className="px-6 py-4 border-b border-gray-100 dark:border-gray-700">
          <h2 className="text-lg font-semibold text-gray-900 dark:text-white">Existing Staff</h2>
        </div>

        {loading ? (
          <div className="p-6 text-sm text-gray-500 dark:text-gray-400">Loading staff users...</div>
        ) : items.length === 0 ? (
          <div className="p-6 text-sm text-gray-500 dark:text-gray-400">No staff users created yet.</div>
        ) : (
          <div className="divide-y divide-gray-100 dark:divide-gray-700">
            {items.map((staff) => (
              <div key={staff.id} className="p-6 flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                <div className="space-y-2 min-w-0">
                  <div className="flex flex-wrap items-center gap-3">
                    <h3 className="text-lg font-semibold text-gray-900 dark:text-white">{staff.name}</h3>
                    <span className={`rounded-full px-2.5 py-1 text-xs font-semibold ${staff.is_active ? "bg-green-100 text-green-700" : "bg-gray-200 text-gray-700"}`}>
                      {staff.is_active ? "ACTIVE" : "INACTIVE"}
                    </span>
                  </div>
                  <div className="grid gap-1 text-sm text-gray-500 dark:text-gray-400 sm:grid-cols-2">
                    <div><span className="font-medium text-gray-700 dark:text-gray-200">Email:</span> {staff.email}</div>
                    <div><span className="font-medium text-gray-700 dark:text-gray-200">Mobile:</span> {staff.mobile || "Not provided"}</div>
                    <div><span className="font-medium text-gray-700 dark:text-gray-200">Role:</span> Staff</div>
                    <div><span className="font-medium text-gray-700 dark:text-gray-200">Created By:</span> {staff.creator?.name || "Primary admin"}</div>
                  </div>
                  <div className="flex flex-wrap gap-2 pt-1">
                    {(staff.permissions || []).length ? (
                      staff.permissions.map((permission) => (
                        <span key={permission} className="rounded-full bg-blue-50 px-2.5 py-1 text-xs font-medium text-blue-700">
                          {permissionsSummary[permission] || permission}
                        </span>
                      ))
                    ) : (
                      <span className="text-sm text-gray-400">No permissions assigned</span>
                    )}
                  </div>
                </div>

                <div className="flex gap-2 shrink-0">
                  <button
                    onClick={() => openEdit(staff)}
                    className="rounded-xl border px-4 py-2 text-sm font-semibold hover:bg-gray-50 dark:hover:bg-gray-700"
                  >
                    Edit
                  </button>
                  <button
                    onClick={() => handleDelete(staff)}
                    className="rounded-xl border border-red-200 px-4 py-2 text-sm font-semibold text-red-600 hover:bg-red-50"
                  >
                    Delete
                  </button>
                </div>
              </div>
            ))}
          </div>
        )}
      </section>

      {editingStaff ? (
        <div className="fixed inset-0 z-50 bg-black/40 p-4 flex items-center justify-center">
          <div className="w-full max-w-4xl rounded-2xl border bg-white dark:bg-gray-800 shadow-xl">
            <div className="px-6 py-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
              <div>
                <h3 className="font-semibold text-gray-900 dark:text-white">Edit Staff User</h3>
                <p className="text-xs text-gray-500 dark:text-gray-400">{editingStaff.email}</p>
              </div>
              <button className="text-sm text-gray-500 hover:text-gray-700" onClick={() => setEditingStaff(null)}>
                Close
              </button>
            </div>

            <div className="p-6 grid gap-4 lg:grid-cols-2">
              <div className="space-y-4">
                <input
                  type="text"
                  placeholder="Full name"
                  value={editForm.name}
                  onChange={(event) => setEditForm((prev) => ({ ...prev, name: event.target.value }))}
                  className="w-full rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2.5 text-sm"
                />
                <input
                  type="email"
                  placeholder="Email"
                  value={editForm.email}
                  onChange={(event) => setEditForm((prev) => ({ ...prev, email: event.target.value }))}
                  className="w-full rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2.5 text-sm"
                />
                <input
                  type="text"
                  placeholder="Mobile"
                  value={editForm.mobile}
                  onChange={(event) => setEditForm((prev) => ({ ...prev, mobile: event.target.value }))}
                  className="w-full rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2.5 text-sm"
                />
                <input
                  type="password"
                  placeholder="New password (optional)"
                  value={editForm.password}
                  onChange={(event) => setEditForm((prev) => ({ ...prev, password: event.target.value }))}
                  className="w-full rounded-xl border border-gray-200 dark:border-gray-700 px-4 py-2.5 text-sm"
                />
                <label className="inline-flex items-center gap-2 text-sm text-gray-700 dark:text-gray-200">
                  <input
                    type="checkbox"
                    checked={editForm.is_active}
                    onChange={(event) => setEditForm((prev) => ({ ...prev, is_active: event.target.checked }))}
                    className="h-4 w-4 rounded border-gray-300"
                  />
                  Active account
                </label>
              </div>

              <PermissionChecklist
                groups={permissionGroups}
                selectedPermissions={editForm.permissions}
                onChange={(permissions) => setEditForm((prev) => ({ ...prev, permissions }))}
              />
            </div>

            <div className="px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-end gap-2">
              <button className="rounded-xl border px-4 py-2 text-sm font-semibold" onClick={() => setEditingStaff(null)}>
                Cancel
              </button>
              <button
                onClick={handleUpdate}
                disabled={savingEdit}
                className="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700 disabled:opacity-60"
              >
                {savingEdit ? "Saving..." : "Save Staff User"}
              </button>
            </div>
          </div>
        </div>
      ) : null}
    </div>
  );
}
