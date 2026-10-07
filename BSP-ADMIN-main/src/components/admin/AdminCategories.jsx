import { useEffect, useMemo, useState } from "react";
import api, { uploadAdminFile } from "../../services/api";
import toast from "react-hot-toast";

const emptyCategoryForm = {
  name: "",
  image: "",
  icon: "",
  is_active: true,
  sort_order: 0,
};

const emptySubcategoryForm = (categoryId = "") => ({
  category_id: categoryId,
  name: "",
  image: "",
  icon: "",
  is_active: true,
  sort_order: 0,
});

const imagePreview = (url) => {
  if (!url) return null;
  return (
    <img
      src={url}
      alt="preview"
      className="h-16 w-16 rounded-lg border border-gray-200 bg-white object-cover dark:border-gray-700 dark:bg-gray-900"
    />
  );
};

const iconPreview = (url) => {
  if (!url) return null;
  return (
    <img
      src={url}
      alt="icon preview"
      className="h-12 w-12 rounded-lg border border-gray-200 bg-white object-contain p-2 dark:border-gray-700 dark:bg-gray-900"
    />
  );
};

const MediaPicker = ({
  label,
  value,
  onUrlChange,
  onFileSelect,
  uploading,
  accept = "image/*",
  type = "image",
}) => {
  return (
    <div className="space-y-2">
      <label className="block text-sm font-medium text-gray-700 dark:text-gray-300">{label}</label>

      <div className="flex flex-col gap-3 sm:flex-row sm:items-start">
        <div className="shrink-0">
          {type === "icon" ? iconPreview(value) : imagePreview(value)}
          {!value && (
            <div className="flex h-16 w-16 items-center justify-center rounded-lg border border-gray-200 bg-gray-50 text-[11px] text-gray-400 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-500">
              No file
            </div>
          )}
        </div>

        <div className="flex-1 space-y-2">
          <input
            type="text"
            value={value || ""}
            onChange={(e) => onUrlChange(e.target.value)}
            placeholder={`${label} URL`}
            className="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-500"
          />

          <label className="inline-flex cursor-pointer items-center gap-2 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-800">
            <span>{uploading ? "Uploading..." : `Upload ${label}`}</span>
            <input
              type="file"
              accept={accept}
              onChange={onFileSelect}
              className="hidden"
              disabled={uploading}
            />
          </label>
        </div>
      </div>
    </div>
  );
};

const AdminCategories = () => {
  const [categories, setCategories] = useState([]);
  const [loadingList, setLoadingList] = useState(false);
  const [search, setSearch] = useState("");

  const [newCategory, setNewCategory] = useState(emptyCategoryForm);
  const [creatingCategory, setCreatingCategory] = useState(false);

  const [expanded, setExpanded] = useState({});
  const [categoryForms, setCategoryForms] = useState({});
  const [subcategoryForms, setSubcategoryForms] = useState({});
  const [newSubForms, setNewSubForms] = useState({});

  const [savingMap, setSavingMap] = useState({});
  const [uploadingMap, setUploadingMap] = useState({});

  const setSaving = (key, value) =>
    setSavingMap((prev) => ({ ...prev, [key]: value }));

  const setUploading = (key, value) =>
    setUploadingMap((prev) => ({ ...prev, [key]: value }));

  const fetchCategories = async () => {
    try {
      setLoadingList(true);
      const res = await api.get("/admin/categories");
      const rows = res.data?.data || [];
      setCategories(rows);

      const nextExpanded = {};
      const nextCategoryForms = {};
      const nextSubcategoryForms = {};
      const nextNewSubForms = {};

      rows.forEach((cat) => {
        nextExpanded[cat.id] = expanded[cat.id] ?? false;

        nextCategoryForms[cat.id] = {
          name: cat.name || "",
          image: cat.image || "",
          icon: cat.icon || "",
          is_active:
            typeof cat.is_active === "boolean"
              ? cat.is_active
              : Boolean(cat.is_active ?? true),
          sort_order: cat.sort_order ?? 0,
        };

        nextNewSubForms[cat.id] = emptySubcategoryForm(cat.id);

        (cat.subcategories || []).forEach((sub) => {
          nextSubcategoryForms[sub.id] = {
            category_id: sub.category_id || cat.id,
            name: sub.name || "",
            image: sub.image || "",
            icon: sub.icon || "",
            is_active:
              typeof sub.is_active === "boolean"
                ? sub.is_active
                : Boolean(sub.is_active ?? true),
            sort_order: sub.sort_order ?? 0,
          };
        });
      });

      setExpanded(nextExpanded);
      setCategoryForms(nextCategoryForms);
      setSubcategoryForms(nextSubcategoryForms);
      setNewSubForms(nextNewSubForms);
    } catch (err) {
      toast.error("Failed to load categories");
    } finally {
      setLoadingList(false);
    }
  };

  useEffect(() => {
    fetchCategories();
    // eslint-disable-next-line react-hooks/exhaustive-deps
  }, []);

  const uploadToSpaces = async (file, folder) => {
    const result = await uploadAdminFile(file, folder);
    return result?.url || "";
  };

  const handleUpload = async ({ file, folder, onDone, uploadKey }) => {
    if (!file) return;

    try {
      setUploading(uploadKey, true);
      const url = await uploadToSpaces(file, folder);
      if (!url) throw new Error("Upload URL not returned");
      onDone(url);
      toast.success("Upload successful");
    } catch (err) {
      console.error(err);
      toast.error(
        err.response?.data?.message ||
          err.message ||
          "File upload failed"
      );
    } finally {
      setUploading(uploadKey, false);
    }
  };

  const handleCreateCategory = async () => {
    if (!newCategory.name.trim()) {
      toast.error("Category name is required");
      return;
    }

    try {
      setCreatingCategory(true);
      await api.post("/admin/categories", {
        name: newCategory.name.trim(),
        image: newCategory.image || null,
        icon: newCategory.icon || null,
        is_active: newCategory.is_active,
        sort_order: Number(newCategory.sort_order || 0),
      });

      toast.success("Category created");
      setNewCategory(emptyCategoryForm);
      await fetchCategories();
    } catch (err) {
      toast.error(err.response?.data?.message || "Failed to create category");
    } finally {
      setCreatingCategory(false);
    }
  };

  const handleUpdateCategory = async (categoryId) => {
    const form = categoryForms[categoryId];
    if (!form?.name?.trim()) {
      toast.error("Category name is required");
      return;
    }

    try {
      setSaving(`category-${categoryId}`, true);

      await api.put(`/admin/categories/${categoryId}`, {
        name: form.name.trim(),
        image: form.image || null,
        icon: form.icon || null,
        is_active: form.is_active,
        sort_order: Number(form.sort_order || 0),
      });

      toast.success("Category updated");
      await fetchCategories();
    } catch (err) {
      toast.error(err.response?.data?.message || "Failed to update category");
    } finally {
      setSaving(`category-${categoryId}`, false);
    }
  };

  const handleDeleteCategory = async (categoryId) => {
    if (!window.confirm("Delete this category?")) return;

    try {
      setSaving(`delete-category-${categoryId}`, true);
      await api.delete(`/admin/categories/${categoryId}`);
      toast.success("Category deleted");
      await fetchCategories();
    } catch (err) {
      toast.error(err.response?.data?.message || "Delete failed");
    } finally {
      setSaving(`delete-category-${categoryId}`, false);
    }
  };

  const handleCreateSubcategory = async (categoryId) => {
    const form = newSubForms[categoryId];
    if (!form?.name?.trim()) {
      toast.error("Subcategory name is required");
      return;
    }

    try {
      setSaving(`create-sub-${categoryId}`, true);

      await api.post("/admin/subcategories", {
        category_id: categoryId,
        name: form.name.trim(),
        image: form.image || null,
        icon: form.icon || null,
        is_active: form.is_active,
        sort_order: Number(form.sort_order || 0),
      });

      toast.success("Subcategory created");
      setNewSubForms((prev) => ({
        ...prev,
        [categoryId]: emptySubcategoryForm(categoryId),
      }));
      await fetchCategories();
    } catch (err) {
      toast.error(err.response?.data?.message || "Failed to create subcategory");
    } finally {
      setSaving(`create-sub-${categoryId}`, false);
    }
  };

  const handleUpdateSubcategory = async (subcategoryId) => {
    const form = subcategoryForms[subcategoryId];
    if (!form?.name?.trim()) {
      toast.error("Subcategory name is required");
      return;
    }

    try {
      setSaving(`subcategory-${subcategoryId}`, true);

      await api.put(`/admin/subcategories/${subcategoryId}`, {
        category_id: form.category_id,
        name: form.name.trim(),
        image: form.image || null,
        icon: form.icon || null,
        is_active: form.is_active,
        sort_order: Number(form.sort_order || 0),
      });

      toast.success("Subcategory updated");
      await fetchCategories();
    } catch (err) {
      toast.error(
        err.response?.data?.message || "Failed to update subcategory"
      );
    } finally {
      setSaving(`subcategory-${subcategoryId}`, false);
    }
  };

  const handleDeleteSubcategory = async (subcategoryId) => {
    if (!window.confirm("Delete this subcategory?")) return;

    try {
      setSaving(`delete-sub-${subcategoryId}`, true);
      await api.delete(`/admin/subcategories/${subcategoryId}`);
      toast.success("Subcategory deleted");
      await fetchCategories();
    } catch (err) {
      toast.error(err.response?.data?.message || "Delete failed");
    } finally {
      setSaving(`delete-sub-${subcategoryId}`, false);
    }
  };

  const orderedCategories = useMemo(() => {
    const query = search.trim().toLowerCase();

    return [...categories]
      .filter((category) => {
        if (!query) return true;

        const categoryName = (category.name || "").toLowerCase();
        const categoryId = String(category.id || "");
        const hasMatchingSubcategory = (category.subcategories || []).some((subcategory) => {
          const subcategoryName = (subcategory.name || "").toLowerCase();
          const subcategoryId = String(subcategory.id || "");

          return subcategoryName.includes(query) || subcategoryId.includes(query);
        });

        return categoryName.includes(query) || categoryId.includes(query) || hasMatchingSubcategory;
      })
      .sort((a, b) => {
        const aOrder = a.sort_order ?? 0;
        const bOrder = b.sort_order ?? 0;
        if (aOrder !== bOrder) return aOrder - bOrder;
        return (a.name || "").localeCompare(b.name || "");
      });
  }, [categories, search]);

  return (
    <div className="p-4 md:p-6">
      <div className="mb-6">
        <h2 className="text-2xl font-bold text-gray-900 dark:text-white">Categories</h2>
        <p className="mt-1 text-sm text-gray-500 dark:text-gray-400">
          Manage category and subcategory name, image and icon from here.
        </p>
        <div className="mt-4 max-w-md">
          <input
            type="text"
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            placeholder="Search categories or subcategories..."
            className="w-full rounded-xl border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-500"
          />
        </div>
      </div>

      <div className="mb-8 rounded-2xl border border-gray-100 bg-white p-4 shadow-sm dark:border-gray-700 dark:bg-gray-800 md:p-5">
        <h3 className="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
          Add New Category
        </h3>

        <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
          <div className="space-y-4">
            <div>
              <label className="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                Category Name
              </label>
              <input
                type="text"
                placeholder="Enter category name"
                value={newCategory.name}
                onChange={(e) =>
                  setNewCategory((prev) => ({ ...prev, name: e.target.value }))
                }
                className="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-500"
              />
            </div>

            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
              <div>
                <label className="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Sort Order
                </label>
                <input
                  type="number"
                  value={newCategory.sort_order}
                  onChange={(e) =>
                    setNewCategory((prev) => ({
                      ...prev,
                      sort_order: e.target.value,
                    }))
                  }
                  className="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                />
              </div>

              <div className="flex items-center gap-2 pt-7">
                <input
                  id="new-category-active"
                  type="checkbox"
                  checked={!!newCategory.is_active}
                  onChange={(e) =>
                    setNewCategory((prev) => ({
                      ...prev,
                      is_active: e.target.checked,
                    }))
                  }
                  className="h-4 w-4"
                />
                <label
                  htmlFor="new-category-active"
                  className="text-sm text-gray-700 dark:text-gray-300"
                >
                  Active
                </label>
              </div>
            </div>
          </div>

          <div className="space-y-4">
            <MediaPicker
              label="Category Image"
              value={newCategory.image}
              uploading={!!uploadingMap["new-category-image"]}
              onUrlChange={(url) =>
                setNewCategory((prev) => ({ ...prev, image: url }))
              }
              onFileSelect={(e) =>
                handleUpload({
                  file: e.target.files?.[0],
                  folder: "categories",
                  uploadKey: "new-category-image",
                  onDone: (url) =>
                    setNewCategory((prev) => ({ ...prev, image: url })),
                })
              }
              type="image"
            />

            <MediaPicker
              label="Category Icon"
              value={newCategory.icon}
              uploading={!!uploadingMap["new-category-icon"]}
              onUrlChange={(url) =>
                setNewCategory((prev) => ({ ...prev, icon: url }))
              }
              onFileSelect={(e) =>
                handleUpload({
                  file: e.target.files?.[0],
                  folder: "categories",
                  uploadKey: "new-category-icon",
                  onDone: (url) =>
                    setNewCategory((prev) => ({ ...prev, icon: url })),
                })
              }
              type="icon"
            />
          </div>
        </div>

        <div className="mt-5">
          <button
            onClick={handleCreateCategory}
            disabled={creatingCategory}
            className="rounded-lg bg-blue-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-60"
          >
            {creatingCategory ? "Creating..." : "Create Category"}
          </button>
        </div>
      </div>

      {loadingList ? (
        <div className="rounded-2xl border border-gray-100 bg-white p-6 text-sm text-gray-500 shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
          Loading categories...
        </div>
      ) : orderedCategories.length === 0 ? (
        <div className="rounded-2xl border border-gray-100 bg-white p-6 text-sm text-gray-500 shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
          No categories found.
        </div>
      ) : (
        <div className="space-y-5">
          {orderedCategories.map((cat) => {
            const catForm = categoryForms[cat.id] || emptyCategoryForm;
            const subCreateForm =
              newSubForms[cat.id] || emptySubcategoryForm(cat.id);
            const query = search.trim().toLowerCase();
            const matchingSubcategories = !query
              ? cat.subcategories || []
              : (cat.subcategories || []).filter((sub) => {
                  const subName = (sub.name || "").toLowerCase();
                  const subId = String(sub.id || "");
                  return subName.includes(query) || subId.includes(query);
                });
            const isExpanded = query ? true : !!expanded[cat.id];

            return (
              <div
                key={cat.id}
                className="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800"
              >
                <div className="flex flex-col gap-3 border-b border-gray-100 px-4 py-4 dark:border-gray-700 md:flex-row md:items-center md:justify-between">
                  <div>
                    <h3 className="text-lg font-semibold text-gray-900 dark:text-white">
                      {cat.name}
                    </h3>
                    <p className="mt-1 text-xs text-gray-500">
                      Category ID: {cat.id} • {cat.subcategories?.length || 0}{" "}
                      subcategories
                    </p>
                  </div>

                  <div className="flex flex-wrap items-center gap-2">
                    <button
                      onClick={() =>
                        setExpanded((prev) => ({
                          ...prev,
                          [cat.id]: !prev[cat.id],
                        }))
                      }
                      disabled={!!query}
                      className="rounded-lg border border-gray-200 bg-white px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-60 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 dark:hover:bg-gray-700"
                    >
                      {query ? "Open by Search" : isExpanded ? "Collapse" : "Expand"}
                    </button>

                    <button
                      onClick={() => handleDeleteCategory(cat.id)}
                      disabled={!!savingMap[`delete-category-${cat.id}`]}
                      className="rounded-lg border border-red-200 px-3 py-2 text-sm text-red-600 hover:bg-red-50 disabled:opacity-60 dark:border-red-900/60 dark:text-red-400 dark:hover:bg-red-950/30"
                    >
                      {savingMap[`delete-category-${cat.id}`]
                        ? "Deleting..."
                        : "Delete Category"}
                    </button>
                  </div>
                </div>

                {isExpanded && (
                  <div className="p-4 md:p-5">
                    <div className="rounded-xl border border-gray-200 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40">
                      <h4 className="mb-4 text-sm font-semibold text-gray-800 dark:text-gray-100">
                        Edit Category
                      </h4>

                      <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
                        <div className="space-y-4">
                          <div>
                            <label className="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                              Category Name
                            </label>
                            <input
                              type="text"
                              value={catForm.name || ""}
                              onChange={(e) =>
                                setCategoryForms((prev) => ({
                                  ...prev,
                                  [cat.id]: {
                                    ...prev[cat.id],
                                    name: e.target.value,
                                  },
                                }))
                              }
                              className="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                            />
                          </div>

                          <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                              <label className="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Sort Order
                              </label>
                              <input
                                type="number"
                                value={catForm.sort_order ?? 0}
                                onChange={(e) =>
                                  setCategoryForms((prev) => ({
                                    ...prev,
                                    [cat.id]: {
                                      ...prev[cat.id],
                                      sort_order: e.target.value,
                                    },
                                  }))
                                }
                                className="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                              />
                            </div>

                            <div className="flex items-center gap-2 pt-7">
                              <input
                                id={`cat-active-${cat.id}`}
                                type="checkbox"
                                checked={!!catForm.is_active}
                                onChange={(e) =>
                                  setCategoryForms((prev) => ({
                                    ...prev,
                                    [cat.id]: {
                                      ...prev[cat.id],
                                      is_active: e.target.checked,
                                    },
                                  }))
                                }
                                className="h-4 w-4"
                              />
                              <label
                                htmlFor={`cat-active-${cat.id}`}
                                className="text-sm text-gray-700 dark:text-gray-300"
                              >
                                Active
                              </label>
                            </div>
                          </div>
                        </div>

                        <div className="space-y-4">
                          <MediaPicker
                            label="Category Image"
                            value={catForm.image}
                            uploading={!!uploadingMap[`cat-image-${cat.id}`]}
                            onUrlChange={(url) =>
                              setCategoryForms((prev) => ({
                                ...prev,
                                [cat.id]: {
                                  ...prev[cat.id],
                                  image: url,
                                },
                              }))
                            }
                            onFileSelect={(e) =>
                              handleUpload({
                                file: e.target.files?.[0],
                                folder: "categories",
                                uploadKey: `cat-image-${cat.id}`,
                                onDone: (url) =>
                                  setCategoryForms((prev) => ({
                                    ...prev,
                                    [cat.id]: {
                                      ...prev[cat.id],
                                      image: url,
                                    },
                                  })),
                              })
                            }
                            type="image"
                          />

                          <MediaPicker
                            label="Category Icon"
                            value={catForm.icon}
                            uploading={!!uploadingMap[`cat-icon-${cat.id}`]}
                            onUrlChange={(url) =>
                              setCategoryForms((prev) => ({
                                ...prev,
                                [cat.id]: {
                                  ...prev[cat.id],
                                  icon: url,
                                },
                              }))
                            }
                            onFileSelect={(e) =>
                              handleUpload({
                                file: e.target.files?.[0],
                                folder: "categories",
                                uploadKey: `cat-icon-${cat.id}`,
                                onDone: (url) =>
                                  setCategoryForms((prev) => ({
                                    ...prev,
                                    [cat.id]: {
                                      ...prev[cat.id],
                                      icon: url,
                                    },
                                  })),
                              })
                            }
                            type="icon"
                          />
                        </div>
                      </div>

                      <div className="mt-5">
                        <button
                          onClick={() => handleUpdateCategory(cat.id)}
                          disabled={!!savingMap[`category-${cat.id}`]}
                          className="rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-blue-700 disabled:opacity-60"
                        >
                          {savingMap[`category-${cat.id}`]
                            ? "Saving..."
                            : "Save Category"}
                        </button>
                      </div>
                    </div>

                    <div className="mt-5">
                      <h4 className="mb-3 text-sm font-semibold text-gray-800 dark:text-gray-100">
                        Subcategories
                      </h4>

                      <div className="space-y-4">
                        {matchingSubcategories.map((sub) => {
                          const subForm =
                            subcategoryForms[sub.id] ||
                            emptySubcategoryForm(cat.id);

                          return (
                            <div
                              key={sub.id}
                              className="rounded-xl border border-gray-200 p-4 dark:border-gray-700"
                            >
                              <div className="mb-3 flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                                <div>
                                  <p className="font-medium text-gray-900 dark:text-white">
                                    {sub.name}
                                  </p>
                                  <p className="text-xs text-gray-500 dark:text-gray-400">
                                    Subcategory ID: {sub.id}
                                  </p>
                                </div>

                                <button
                                  onClick={() => handleDeleteSubcategory(sub.id)}
                                  disabled={!!savingMap[`delete-sub-${sub.id}`]}
                                  className="rounded-lg border border-red-200 px-3 py-2 text-sm text-red-600 hover:bg-red-50 disabled:opacity-60 dark:border-red-900/60 dark:text-red-400 dark:hover:bg-red-950/30"
                                >
                                  {savingMap[`delete-sub-${sub.id}`]
                                    ? "Deleting..."
                                    : "Delete Subcategory"}
                                </button>
                              </div>

                              <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
                                <div className="space-y-4">
                                  <div>
                                    <label className="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                      Subcategory Name
                                    </label>
                                    <input
                                      type="text"
                                      value={subForm.name || ""}
                                      onChange={(e) =>
                                        setSubcategoryForms((prev) => ({
                                          ...prev,
                                          [sub.id]: {
                                            ...prev[sub.id],
                                            name: e.target.value,
                                          },
                                        }))
                                      }
                                      className="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                    />
                                  </div>

                                  <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                      <label className="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Sort Order
                                      </label>
                                      <input
                                        type="number"
                                        value={subForm.sort_order ?? 0}
                                        onChange={(e) =>
                                          setSubcategoryForms((prev) => ({
                                            ...prev,
                                            [sub.id]: {
                                              ...prev[sub.id],
                                              sort_order: e.target.value,
                                            },
                                          }))
                                        }
                                        className="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                      />
                                    </div>

                                    <div className="flex items-center gap-2 pt-7">
                                      <input
                                        id={`sub-active-${sub.id}`}
                                        type="checkbox"
                                        checked={!!subForm.is_active}
                                        onChange={(e) =>
                                          setSubcategoryForms((prev) => ({
                                            ...prev,
                                            [sub.id]: {
                                              ...prev[sub.id],
                                              is_active: e.target.checked,
                                            },
                                          }))
                                        }
                                        className="h-4 w-4"
                                      />
                                      <label
                                        htmlFor={`sub-active-${sub.id}`}
                                        className="text-sm text-gray-700 dark:text-gray-300"
                                      >
                                        Active
                                      </label>
                                    </div>
                                  </div>
                                </div>

                                <div className="space-y-4">
                                  <MediaPicker
                                    label="Subcategory Image"
                                    value={subForm.image}
                                    uploading={!!uploadingMap[`sub-image-${sub.id}`]}
                                    onUrlChange={(url) =>
                                      setSubcategoryForms((prev) => ({
                                        ...prev,
                                        [sub.id]: {
                                          ...prev[sub.id],
                                          image: url,
                                        },
                                      }))
                                    }
                                    onFileSelect={(e) =>
                                      handleUpload({
                                        file: e.target.files?.[0],
                                        folder: "subcategories",
                                        uploadKey: `sub-image-${sub.id}`,
                                        onDone: (url) =>
                                          setSubcategoryForms((prev) => ({
                                            ...prev,
                                            [sub.id]: {
                                              ...prev[sub.id],
                                              image: url,
                                            },
                                          })),
                                      })
                                    }
                                    type="image"
                                  />

                                  <MediaPicker
                                    label="Subcategory Icon"
                                    value={subForm.icon}
                                    uploading={!!uploadingMap[`sub-icon-${sub.id}`]}
                                    onUrlChange={(url) =>
                                      setSubcategoryForms((prev) => ({
                                        ...prev,
                                        [sub.id]: {
                                          ...prev[sub.id],
                                          icon: url,
                                        },
                                      }))
                                    }
                                    onFileSelect={(e) =>
                                      handleUpload({
                                        file: e.target.files?.[0],
                                        folder: "subcategories",
                                        uploadKey: `sub-icon-${sub.id}`,
                                        onDone: (url) =>
                                          setSubcategoryForms((prev) => ({
                                            ...prev,
                                            [sub.id]: {
                                              ...prev[sub.id],
                                              icon: url,
                                            },
                                          })),
                                      })
                                    }
                                    type="icon"
                                  />
                                </div>
                              </div>

                              <div className="mt-4">
                                <button
                                  onClick={() => handleUpdateSubcategory(sub.id)}
                                  disabled={!!savingMap[`subcategory-${sub.id}`]}
                                  className="rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-700 disabled:opacity-60"
                                >
                                  {savingMap[`subcategory-${sub.id}`]
                                    ? "Saving..."
                                    : "Save Subcategory"}
                                </button>
                              </div>
                            </div>
                          );
                        })}
                      </div>

                      <div className="mt-5 rounded-xl border border-dashed border-gray-300 bg-gray-50 p-4 dark:border-gray-700 dark:bg-gray-900/40">
                        <h5 className="mb-4 text-sm font-semibold text-gray-800 dark:text-gray-100">
                          Add New Subcategory
                        </h5>

                        <div className="grid grid-cols-1 gap-4 lg:grid-cols-2">
                          <div className="space-y-4">
                            <div>
                              <label className="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Subcategory Name
                              </label>
                              <input
                                type="text"
                                value={subCreateForm.name || ""}
                                onChange={(e) =>
                                  setNewSubForms((prev) => ({
                                    ...prev,
                                    [cat.id]: {
                                      ...prev[cat.id],
                                      name: e.target.value,
                                    },
                                  }))
                                }
                                placeholder="Enter subcategory name"
                                className="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white dark:placeholder:text-gray-500"
                              />
                            </div>

                            <div className="grid grid-cols-1 gap-4 sm:grid-cols-2">
                              <div>
                                <label className="mb-2 block text-sm font-medium text-gray-700 dark:text-gray-300">
                                  Sort Order
                                </label>
                                <input
                                  type="number"
                                  value={subCreateForm.sort_order ?? 0}
                                  onChange={(e) =>
                                    setNewSubForms((prev) => ({
                                      ...prev,
                                      [cat.id]: {
                                        ...prev[cat.id],
                                        sort_order: e.target.value,
                                      },
                                    }))
                                  }
                                  className="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none dark:border-gray-700 dark:bg-gray-900 dark:text-white"
                                />
                              </div>

                              <div className="flex items-center gap-2 pt-7">
                                <input
                                  id={`new-sub-active-${cat.id}`}
                                  type="checkbox"
                                  checked={!!subCreateForm.is_active}
                                  onChange={(e) =>
                                    setNewSubForms((prev) => ({
                                      ...prev,
                                      [cat.id]: {
                                        ...prev[cat.id],
                                        is_active: e.target.checked,
                                      },
                                    }))
                                  }
                                  className="h-4 w-4"
                                />
                                <label
                                  htmlFor={`new-sub-active-${cat.id}`}
                                  className="text-sm text-gray-700 dark:text-gray-300"
                                >
                                  Active
                                </label>
                              </div>
                            </div>
                          </div>

                          <div className="space-y-4">
                            <MediaPicker
                              label="Subcategory Image"
                              value={subCreateForm.image}
                              uploading={!!uploadingMap[`new-sub-image-${cat.id}`]}
                              onUrlChange={(url) =>
                                setNewSubForms((prev) => ({
                                  ...prev,
                                  [cat.id]: {
                                    ...prev[cat.id],
                                    image: url,
                                  },
                                }))
                              }
                              onFileSelect={(e) =>
                                handleUpload({
                                  file: e.target.files?.[0],
                                  folder: "subcategories",
                                  uploadKey: `new-sub-image-${cat.id}`,
                                  onDone: (url) =>
                                    setNewSubForms((prev) => ({
                                      ...prev,
                                      [cat.id]: {
                                        ...prev[cat.id],
                                        image: url,
                                      },
                                    })),
                                })
                              }
                              type="image"
                            />

                            <MediaPicker
                              label="Subcategory Icon"
                              value={subCreateForm.icon}
                              uploading={!!uploadingMap[`new-sub-icon-${cat.id}`]}
                              onUrlChange={(url) =>
                                setNewSubForms((prev) => ({
                                  ...prev,
                                  [cat.id]: {
                                    ...prev[cat.id],
                                    icon: url,
                                  },
                                }))
                              }
                              onFileSelect={(e) =>
                                handleUpload({
                                  file: e.target.files?.[0],
                                  folder: "subcategories",
                                  uploadKey: `new-sub-icon-${cat.id}`,
                                  onDone: (url) =>
                                    setNewSubForms((prev) => ({
                                      ...prev,
                                      [cat.id]: {
                                        ...prev[cat.id],
                                        icon: url,
                                      },
                                    })),
                                })
                              }
                              type="icon"
                            />
                          </div>
                        </div>

                        <div className="mt-5">
                          <button
                            onClick={() => handleCreateSubcategory(cat.id)}
                            disabled={!!savingMap[`create-sub-${cat.id}`]}
                            className="rounded-lg bg-green-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-green-700 disabled:opacity-60"
                          >
                            {savingMap[`create-sub-${cat.id}`]
                              ? "Creating..."
                              : "Create Subcategory"}
                          </button>
                        </div>
                      </div>
                    </div>
                  </div>
                )}
              </div>
            );
          })}
        </div>
      )}
    </div>
  );
};

export default AdminCategories;
