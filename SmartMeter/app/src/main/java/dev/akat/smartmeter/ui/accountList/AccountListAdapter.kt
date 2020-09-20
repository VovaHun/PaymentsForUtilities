package dev.akat.smartmeter.ui.accountList

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import dev.akat.smartmeter.R
import dev.akat.smartmeter.model.AccountSummary
import kotlinx.android.synthetic.main.item_account_list.view.*

class AccountListAdapter(private val eventListener: EventListener) :
    ListAdapter<AccountSummary, AccountListAdapter.ViewHolder>(
        object : DiffUtil.ItemCallback<AccountSummary>() {
            override fun areItemsTheSame(oldItem: AccountSummary, newItem: AccountSummary): Boolean {
                return oldItem.id == newItem.id
            }

            override fun areContentsTheSame(oldItem: AccountSummary, newItem: AccountSummary): Boolean {
                return oldItem == newItem
            }
        }
    ) {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int) = ViewHolder(
        LayoutInflater.from(parent.context).inflate(
            R.layout.item_account_list, parent, false
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
        fun bind(item: AccountSummary) = with(itemView) {
            acc_item_company_tv.text = item.companyName
            acc_item_account_tv.text = item.accountName
            acc_item_abonent_tv.text = item.abonentName
            acc_item_object_tv.text = item.objectName
            acc_item_address_tv.text = item.objectAddress

            setOnClickListener {
                eventListener.onItemClick(item.id)
            }
        }
    }
}