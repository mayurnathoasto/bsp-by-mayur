
import { useEffect, useState } from "react";
import { Search, User } from "lucide-react";
import toast from "react-hot-toast";
import { getBuyers } from "../../services/api";

export default function AdminUsers() {
  const [buyers, setBuyers] = useState([]);
  const [filteredBuyers, setFilteredBuyers] = useState([]);
  const [loading, setLoading] = useState(true);
  const [search, setSearch] = useState("");

  useEffect(() => {
    loadBuyers();
  }, []);

  useEffect(() => {
    const q = search.trim().toLowerCase();

    if (!q) {
      setFilteredBuyers(buyers);
      return;
    }

    const next = buyers.filter((buyer) => {
      const name = (buyer.name || "").toLowerCase();
      const phone = (buyer.phone || "").toLowerCase();
      const email = (buyer.email || "").toLowerCase();

      return (
        name.includes(q) ||
        phone.includes(q) ||
        email.includes(q)
      );
    });

    setFilteredBuyers(next);
  }, [search, buyers]);

  const loadBuyers = async () => {
    try {
      setLoading(true);
      const res = await getBuyers();
      const rows = res.data?.data || [];
      setBuyers(rows);
      setFilteredBuyers(rows);
    } catch (err) {
      console.error(err);
      toast.error("Failed to load buyers");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="space-y-6 animate-fade-in">
      <div className="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
          <h1 className="text-3xl font-bold text-gray-900 dark:text-white">
            Buyer Management
          </h1>
          <p className="text-gray-500 dark:text-gray-400 mt-1">
            Users who have not become sellers yet
          </p>
        </div>
      </div>

      <div className="flex items-center gap-4 bg-white dark:bg-gray-800 p-4 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700">
        <div className="relative flex-1">
          <Search
            className="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"
            size={18}
          />
          <input
            type="text"
            placeholder="Search buyers..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="w-full pl-10 pr-4 py-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none transition-all"
          />
        </div>
      </div>

      <div className="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        {loading ? (
          <div className="p-12 text-center text-gray-500">Loading buyers...</div>
        ) : (
          <div className="overflow-x-auto">
            <table className="w-full text-left">
              <thead className="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-100 dark:border-gray-700">
                <tr>
                  <th className="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                    Buyer Details
                  </th>
                  <th className="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                    Mobile
                  </th>
                  <th className="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                    Email
                  </th>
                  <th className="px-6 py-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider whitespace-nowrap">
                    Joined Date
                  </th>
                </tr>
              </thead>

              <tbody className="divide-y divide-gray-100 dark:divide-gray-700">
                {filteredBuyers.map((buyer) => (
                  <tr
                    key={buyer.id}
                    className="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150"
                  >
                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="flex items-center">
                        <div className="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold mr-4">
                          {buyer.name ? buyer.name[0].toUpperCase() : <User size={20} />}
                        </div>
                        <div>
                          <div className="font-semibold text-gray-900 dark:text-white">
                            {buyer.name || "N/A"}
                          </div>
                          <div className="text-xs text-gray-400">
                            ID: {buyer.id}
                          </div>
                        </div>
                      </div>
                    </td>

                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm text-gray-500 dark:text-gray-400">
                        {buyer.phone || "-"}
                      </div>
                    </td>

                    <td className="px-6 py-4 whitespace-nowrap">
                      <div className="text-sm text-gray-500 dark:text-gray-400">
                        {buyer.email || "-"}
                      </div>
                    </td>

                    <td className="px-6 py-4 text-sm text-gray-500 dark:text-gray-400 whitespace-nowrap">
                      {buyer.created_at
                        ? new Date(buyer.created_at).toLocaleDateString()
                        : "-"}
                    </td>
                  </tr>
                ))}

                {filteredBuyers.length === 0 && (
                  <tr>
                    <td colSpan="4" className="text-center py-12 text-gray-500">
                      No buyers found.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        )}
      </div>
    </div>
  );
}