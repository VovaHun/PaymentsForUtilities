package dev.akat.smartmeter.ui.accountInfo

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView
import dev.akat.smartmeter.R
import dev.akat.smartmeter.model.Device
import kotlinx.android.synthetic.main.item_device_list.view.*

class DeviceListAdapter(private val eventListener: EventListener, private val hasAccess: Boolean) :
    ListAdapter<Device, DeviceListAdapter.ViewHolder>(
        object : DiffUtil.ItemCallback<Device>() {
            override fun areItemsTheSame(oldItem: Device, newItem: Device): Boolean {
                return oldItem.id == newItem.id
            }

            override fun areContentsTheSame(oldItem: Device, newItem: Device): Boolean {
                return oldItem == newItem
            }
        }
    ) {

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int) = ViewHolder(
        LayoutInflater.from(parent.context).inflate(
            R.layout.item_device_list, parent, false
        ), eventListener, hasAccess
    )

    override fun onBindViewHolder(holder: ViewHolder, position: Int) {
        holder.bind(getItem(position))
    }

    interface EventListener {
        fun onSendClick(deviceId: Long, indication: Double)
    }

    class ViewHolder(
        itemView: View,
        private val eventListener: EventListener,
        private val hasAccess: Boolean
    ) :
        RecyclerView.ViewHolder(itemView) {
        fun bind(item: Device) = with(itemView) {
            device_name_tv.text = item.name
            device_previous_value_tv.text = item.previousIndications.toString()
            device_current_value_tv.text = item.currentIndications.toString()

            if (hasAccess) {
                device_new_indication_et.visibility = View.VISIBLE
                device_send_btn.visibility = View.VISIBLE
            } else {
                device_new_indication_et.visibility = View.GONE
                device_send_btn.visibility = View.GONE
            }

            device_send_btn.setOnClickListener {
                val newIndication = device_new_indication_et.text.toString().toDouble()
                eventListener.onSendClick(item.id, newIndication)
            }
        }
    }
}