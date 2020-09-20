package dev.akat.smartmeter.ui.companyList

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import dev.akat.smartmeter.R
import dev.akat.smartmeter.model.Company
import kotlinx.android.synthetic.main.item_company_list.view.*

class CompanyListAdapter(private val eventListener: EventListener) :
    ListAdapter<Company, CompanyListAdapter.ViewHolder>(
        object : DiffUtil.ItemCallback<Company>() {
            override fun areItemsTheSame(oldItem: Company, newItem: Company): Boolean {
                return oldItem.id == newItem.id
            }

            override fun areContentsTheSame(oldItem: Company, newItem: Company): Boolean {
                return oldItem == newItem
            }
        }
    ) {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int) = ViewHolder(
        LayoutInflater.from(parent.context).inflate(
            R.layout.item_company_list, parent, false
        ), eventListener
    )

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        holder.bind(getItem(position))
    }

    interface EventListener {
        fun onItemClick(id: Long)
    }

    class ViewHolder(itemView: View, private val eventListener: EventListener) :
        RecyclerView.ViewHolder(itemView) {
        fun bind(item: Company) = with(itemView) {
            company_item_name_tv.text = item.name

            setOnClickListener {
                eventListener.onItemClick(item.id)
            }
        }
    }
}