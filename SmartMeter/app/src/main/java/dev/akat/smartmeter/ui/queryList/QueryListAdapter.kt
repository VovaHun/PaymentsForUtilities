package dev.akat.smartmeter.ui.queryList

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import dev.akat.smartmeter.R
import dev.akat.smartmeter.model.QueryInfo
import dev.akat.smartmeter.utils.QUERY_ANSWER_REJECT
import kotlinx.android.synthetic.main.item_query_list.view.*

class QueryListAdapter() :
    ListAdapter<QueryInfo, QueryListAdapter.ViewHolder>(
        object : DiffUtil.ItemCallback<QueryInfo>() {
            override fun areItemsTheSame(oldItem: QueryInfo, newItem: QueryInfo): Boolean {
                return oldItem.query.id == newItem.query.id
            }

            override fun areContentsTheSame(oldItem: QueryInfo, newItem: QueryInfo): Boolean {
                return oldItem == newItem
            }
        }
    ) {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int) = ViewHolder(
        LayoutInflater.from(parent.context).inflate(
            R.layout.item_query_list, parent, false
        )
    )

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        holder.bind(getItem(position))
    }

    class ViewHolder(itemView: View) :
        RecyclerView.ViewHolder(itemView) {
        fun bind(item: QueryInfo) = with(itemView) {
            query_item_date_tv.text = resources.getString(R.string.date_format, item.query.date)
            query_item_status_tv.text = item.query.statusName
            query_item_company_tv.text = item.company.name
            query_item_text_tv.text = item.query.text
            query_item_answer_tv.text = item.query.answer

            if (item.query.answer == QUERY_ANSWER_REJECT)
                query_item_answer_tv.visibility = View.VISIBLE
            else query_item_answer_tv.visibility = View.GONE
        }
    }
}